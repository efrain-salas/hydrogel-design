window.selectedNode = null;
window.lastElementClickedAt = (new Date()).getTime();
window.container = document.getElementById('stage');
window.btDelete = document.getElementById('bt-delete');
window.btPrint = document.getElementById('bt-print');
window.selectFontFamilies = document.getElementById('select-font-families');
window.selectFontSizes = document.getElementById('select-font-sizes');
window.selectTextAlignments = document.getElementById('select-text-alignment');
window.selectTextColors = document.getElementById('select-text-colors');
window.textarea = null;

container.addEventListener('click', () => {
    onContainerClicked();
});
container.addEventListener('touchend', () => {
    onContainerClicked();
});

document.addEventListener('livewire:load', () => {
    Livewire.on('pltFileReady', (plt) => {
        drawPlt(plt);
    });

    Livewire.emit('frontendReady');
});

document.addEventListener('alpine:init', () => {
    Alpine.store('global', {
        fontFamily: 'Roboto',
        fontSize: 20,
        textColor: 'black',
        textAlign: 'center',
    });
});

// GLOBAL VARIABLES
window.safeAreaX = null;
window.safeAreaY = null;
window.safeAreaWidth = null;
window.safeAreaHeight = null;

// STAGE
window.stage = new Konva.Stage({
    container: 'stage',
    width: 1428 / 4,
    height: 2220 / 4,
});

// LAYERS
window.frameLayer = new Konva.Layer();
stage.add(frameLayer);

window.userBackgroundLayer = new Konva.Layer({
    //opacity: 0.8,
});
stage.add(userBackgroundLayer);

window.userForegroundLayer = new Konva.Layer();
stage.add(userForegroundLayer);

// TRANSFORMER: Resize, etc
window.transformer = new Konva.Transformer();
userBackgroundLayer.add(transformer);

window.transformer = new Konva.Transformer();
userForegroundLayer.add(transformer);

//userLayer.draw();

// FUNCTIONS
window.addImage = (layer, url, x, y, width, height) => {
    width = width || safeAreaWidth;
    height = height || safeAreaHeight;
    x = x || safeAreaX + (safeAreaWidth / 2) - (width / 2);
    y = y || safeAreaY + (safeAreaHeight / 2) - (height / 2);

    Konva.Image.fromURL(url, (imageShape) => {
        imageShape.setAttrs({
            x: x,
            y: y,
            width: width,
            height: height,
            draggable: true,
        });
        imageShape.on('click tap', (e) => {
            imageShape.moveToTop();
            transformer.nodes([imageShape]);
            transformer.enabledAnchors([
                'top-left',
                //'top-center',
                'top-right',
                //'middle-right',
                //'middle-left',
                'bottom-left',
                //'bottom-center',
                'bottom-right',
            ]);
            lastElementClickedAt = (new Date()).getTime();
            onNodeSelected(imageShape);
        });

        layer.add(imageShape);

        onNodeAdded(imageShape);
    });
};

window.addEmoji = (url) => {
    addImage(userForegroundLayer, url, null, null, 100, 100);
};

window.addUserImage = (layer) => {
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();

    reader.addEventListener('load', () => {
        const img = new Image();

        img.onload = () => {
            const aspectRatio = img.width / img.height;
            const width = safeAreaWidth;
            const height = safeAreaWidth / aspectRatio;

            addImage(layer, reader.result, null, null, width, height);
        };

        img.src = reader.result;
    }, false);

    reader.readAsDataURL(file);
};

window.addUserForegroundImage = () => {
    addUserImage(userForegroundLayer);
};

window.addUserBackgroundImage = () => {
    addUserImage(userBackgroundLayer);
};

window.addSvgPath = (layer, pathString) => {
    const path = new Konva.Path({
        data: pathString,
        stroke: 'black',
        strokeWidth: 3,
        width: 300,
        draggable: true,
    });

    layer.add(path);

    onNodeAdded(path);
};

window.addText = () => {
    const textNode = new Konva.Text({
        text: 'Nuevo texto',
        x: safeAreaX + (safeAreaWidth / 2) - 100,
        y: safeAreaY + (safeAreaHeight / 2) - 10,
        fontFamily: Alpine.store('global').fontFamily,
        fontSize: Alpine.store('global').fontSize,
        align: Alpine.store('global').textAlign,
        draggable: true,
        width: 200,
        fill: Alpine.store('global').textColor,
    });

    textNode.on('click tap', (e) => {
        textNode.moveToTop();
        transformer.nodes([textNode]);
        transformer.enabledAnchors([
            //'top-left',
            //'top-center',
            //'top-right',
            'middle-right',
            'middle-left',
            //'bottom-left',
            //'bottom-center',
            //'bottom-right',
        ]);
        lastElementClickedAt = (new Date()).getTime();
        onNodeSelected(textNode);
    });

    textNode.on('transform', function () {
        // reset scale, so only with is changing by transformer
        textNode.setAttrs({
            width: textNode.width() * textNode.scaleX(),
            scaleX: 1,
        });
    });

    textNode.on('dblclick dbltap', () => {
        // hide text node and transformer:
        textNode.hide();
        transformer.hide();

        // create textarea over canvas with absolute position
        // first we need to find position for textarea
        // how to find it?

        // at first lets find position of text node relative to the stage:
        const textPosition = textNode.absolutePosition();

        // so position of textarea will be the sum of positions above:
        const areaPosition = {
            x: stage.container().offsetLeft + textPosition.x,
            y: stage.container().offsetTop + textPosition.y,
        };

        // create textarea and style it
        textarea = document.createElement('textarea');
        document.body.appendChild(textarea);

        // apply many styles to match text on canvas as close as possible
        // remember that text rendering on canvas and on the textarea can be different
        // and sometimes it is hard to make it 100% the same. But we will try...
        textarea.value = textNode.text();
        textarea.style.position = 'absolute';
        textarea.style.top = areaPosition.y + 'px';
        textarea.style.left = areaPosition.x + 'px';
        textarea.style.width = textNode.width() - textNode.padding() * 2 + 'px';
        textarea.style.height = textNode.height() - textNode.padding() * 2 + 5 + 'px';
        textarea.style.fontSize = textNode.fontSize() + 'px';
        textarea.style.border = '1px dashed #DDD';
        textarea.style.padding = '0px';
        textarea.style.margin = '0px';
        textarea.style.overflow = 'hidden';
        textarea.style.background = 'none';
        textarea.style.outline = 'none';
        textarea.style.resize = 'none';
        textarea.style.lineHeight = textNode.lineHeight();
        textarea.style.fontFamily = textNode.fontFamily();
        textarea.style.transformOrigin = 'left top';
        textarea.style.textAlign = textNode.align();
        textarea.style.color = textNode.fill();

        const rotation = textNode.rotation();
        let transform = '';
        if (rotation) {
            transform += 'rotateZ(' + rotation + 'deg)';
        }

        let px = 0;
        // also we need to slightly move textarea on firefox
        // because it jumps a bit
        const isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
        if (isFirefox) {
            px += 2 + Math.round(textNode.fontSize() / 20);
        }
        transform += 'translateY(-' + px + 'px)';

        textarea.style.transform = transform;

        // reset height
        textarea.style.height = 'auto';
        // after browsers resized it we can set actual value
        textarea.style.height = textarea.scrollHeight + 3 + 'px';

        textarea.focus();
        textarea.select();

        /*function removeTextarea() {
            textarea.parentNode.removeChild(textarea);
            window.removeEventListener('click', handleOutsideClick);
            textNode.show();
            transformer.show();
            transformer.forceUpdate();
            textarea = null;
        }*/

        function setTextareaWidth(newWidth) {
            if (!newWidth) {
                // set width for placeholder
                newWidth = textNode.placeholder.length * textNode.fontSize();
            }
            // some extra fixes on different browsers
            const isSafari = /^((?!chrome|android).)*safari/i.test(
                navigator.userAgent
            );
            const isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
            if (isSafari || isFirefox) {
                newWidth = Math.ceil(newWidth);
            }

            const isEdge = document.documentMode || /Edge/.test(navigator.userAgent);
            if (isEdge) {
                newWidth += 1;
            }
            textarea.style.width = newWidth + 'px';
        }

        textarea.addEventListener('keydown', function (e) {
            // hide on enter
            // but don't hide on shift + enter
            /*if (e.keyCode === 13 && !e.shiftKey) {
                textNode.text(textarea.value);
                removeTextarea();
            }*/
            // on esc do not set value back to node
            if (e.keyCode === 27) {
                onContainerClicked();
            }
        });

        textarea.addEventListener('keydown', function (e) {
            if (textarea) {
                const scale = textNode.getAbsoluteScale().x;
                setTextareaWidth(textNode.width() * scale);
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + textNode.fontSize() + 'px';
            }
        });

        /*function handleOutsideClick(e) {
            if (e.target !== textarea) {
                textNode.text(textarea.value);
                removeTextarea();
            }
        }
        setTimeout(() => {
            window.addEventListener('click', handleOutsideClick);
        });*/
    });

    userForegroundLayer.add(textNode);

    onNodeAdded(textNode);
};

window.changeSelectedTextFontFamily = (fontFamily) => {
    selectedNode.fontFamily(fontFamily);
    if (textarea) textarea.style.fontFamily = selectedNode.fontFamily();
    Alpine.store('global').fontFamily = fontFamily;
    transformer.forceUpdate();
};

window.changeSelectedTextFontSize = (fontSize) => {
    selectedNode.fontSize(fontSize);
    if (textarea) textarea.style.fontSize = selectedNode.fontSize() + 'px';
    Alpine.store('global').fontSize = fontSize;
    transformer.forceUpdate();
};

window.changeSelectedTextAlignment = (alignment) => {
    selectedNode.align(alignment);
    if (textarea) textarea.style.textAlign = selectedNode.align();
    Alpine.store('global').textAlign = alignment;
};

window.changeSelectedTextColor = (color) => {
    selectedNode.fill(color);
    if (textarea) textarea.style.color = selectedNode.fill();
    Alpine.store('global').textColor = color;
};

window.deleteSelected = () => {
    selectedNode.destroy();
    onContainerClicked();
};

window.getStageAsDataUrl = () => {
    frame.hide();
    plt.hide();
    //userBackgroundLayer.opacity(1);

    const url = stage.toDataURL({
        pixelRatio: 4,
    });

    frame.show();
    plt.show();
    //userBackgroundLayer.opacity(0.8);

    return url;
};

window.downloadDataUrl = (url) => {
    const link = document.createElement('a');
    link.download = name;
    link.href = url;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

window.downloadStage = () => {
    downloadDataUrl(getStageAsDataUrl());
};

window.printStage = () => {
    if (confirm('¿Deseas mandar ya a imprimir tu diseño? Una vez lo hayas hecho no podrás hacer ninguna modificación.')) {
        Livewire.emit('print', getStageAsDataUrl());
    }
};

// EVENTS LISTERNERS
window.onNodeAdded = () => {
    setTimeout(() => {
        stage.draw();
    }, 200);
};

window.onContainerClicked = () => {
    if ((new Date()).getTime() - lastElementClickedAt > 500) {
        if (transformer) {
            transformer.nodes([]);
        }

        if (selectedNode && textarea && selectedNode.getClassName() === 'Text') {
            selectedNode.text(textarea.value);
            selectedNode.show();
            transformer.show();
            transformer.forceUpdate();
        }

        selectedNode = null;

        if (textarea) {
            textarea.parentNode.removeChild(textarea);
            textarea = null;
        }

        btDelete.classList.add('hidden');
        selectFontFamilies.classList.add('hidden');
        selectFontSizes.classList.add('hidden');
        selectTextAlignments.classList.add('hidden');
        selectTextColors.classList.add('hidden');
    }
};

window.onNodeSelected = (node) => {
    selectedNode = node;

    btDelete.classList.remove('hidden');

    if (node.getClassName() === 'Text') {
        selectFontFamilies.classList.remove('hidden');
        selectFontSizes.classList.remove('hidden');
        selectTextAlignments.classList.remove('hidden');
        selectTextColors.classList.remove('hidden');
    }
};

// --------------------------

/*
// create our shape
const circle = new Konva.Circle({
    x: stage.width() / 2,
    y: stage.height() / 2,
    radius: 70,
    fill: 'red',
    stroke: 'black',
    strokeWidth: 4,
    draggable: true,
});

// add the shape to the layer
userLayer.add(circle);

 */

window.procLine = (line) => {
    let i = 0;
    let signZ = 1;

    while(line[i]<'0' || line[i]>'9'){
        if (line[i] === 'U') {
            signZ = 0;
        }
        i++;
    }
    if (i === line.length)
        return NaN;
    line = line.substr(i,line.length-i);
    const strs = line.split(',');
    const axis_x = parseInt(strs[0]);
    const axis_y = parseInt(strs[1]);
    if (isNaN(axis_x) || isNaN(axis_y))
        return NaN;
    return {x:axis_x,y:axis_y,z:signZ};
};

window.drawPlt = (pltstr) => {
    const width_edge = 20;
    const width_total = 119;
    const height_total = 185;
    const width_box = width_total - width_edge;
    const height_box = height_total;
    const const_ratio = 3;

    const background = new Konva.Rect({
        width: stage.width(),
        height: stage.height(),
        fill: 'white',
    });

    window.frame = new Konva.Rect({
        width: stage.width(),
        height: stage.height(),
        strokeWidth: 2,
        stroke: 'black',
        fill: 'white',
    });

    const delimiter = new Konva.Shape({
        width: stage.width(),
        height: stage.height(),
        strokeWidth: 2,
        stroke: 'black',
        sceneFunc: (context, shape) => {
            context.beginPath();
            context.setLineDash([5]);
            context.moveTo(width_edge * const_ratio, 0);
            context.lineTo(width_edge * const_ratio, shape.height());

            context.fillStrokeShape(shape);
        },
    });

    const topArrow = new Konva.Shape({
        width: stage.width(),
        height: stage.height(),
        fill: 'black',
        sceneFunc: (context, shape) => {
            context.beginPath();
            context.moveTo(30, 10);
            context.lineTo(20, 30);
            context.lineTo(25, 30);
            context.lineTo(25, 50);
            context.lineTo(35, 50);
            context.lineTo(35, 30);
            context.lineTo(40, 30);
            context.lineTo(30, 10);

            context.fillStrokeShape(shape);
        },
    });

    const topText = new Konva.Text({
        x: 13,
        y: 60,
        text: 'TOP',
        fontSize: 18,
        fontFamily: 'Arial',
        fill: 'black',
    });

    const bottomArrow = new Konva.Shape({
        width: stage.width(),
        height: stage.height(),
        fill: 'black',
        sceneFunc: (context, shape) => {
            context.beginPath();
            const py = shape.height() - 40;
            context.moveTo(10, py + 10);
            context.lineTo(30, py);
            context.lineTo(30, py + 5);
            context.lineTo(50, py + 5);
            context.lineTo(50, py + 15);
            context.lineTo(30, py + 15);
            context.lineTo(30, py + 20);
            context.lineTo(10, py + 10);

            context.fillStrokeShape(shape);
        },
    });

    window.plt = new Konva.Shape({
        strokeWidth: 1,
        stroke: 'black',
        sceneFunc: (context, shape) => {
            let line = "";
            let comma = 0;
            const points = [];
            for (let i = 0; i < pltstr.length; i++) {
                const ch = pltstr[i];
                if (ch === ';') {
                    const point = procLine(line);
                    if (point)
                        points.push(point);
                    line = "";
                    comma = 0;
                } else {
                    line += pltstr[i];
                    if (ch === ',' || ch === ' ') {
                        comma++;
                    }
                    if (comma === 2) {
                        const point = procLine(line);
                        if (point)
                            points.push(point);
                        comma = 0;
                        line = "";
                    }
                }
            }

            let max_x = points[0].x;
            let max_y = points[0].y;
            let min_x = points[0].x;
            let min_y = points[0].y;
            for (let i = 0; i < points.length; i++) {
                if (points[i].x > max_x)
                    max_x = points[i].x;
                if (points[i].y > max_y)
                    max_y = points[i].y;
                if (points[i].x < min_x)
                    min_x = points[i].x;
                if (points[i].y < min_y)
                    min_y = points[i].y;
            }

            const width = (max_x - min_x) / 40;
            const height = (max_y - min_y) / 40;
            const width_phone = width * const_ratio;
            const height_phone = height * const_ratio;

            context.beginPath();
            context.setLineDash([]);
            const center_x = (width_edge + width_box / 2) * const_ratio;
            const center_y = height_box / 2 * const_ratio;
            const offset_x = (width_box - width) / 2 * const_ratio;
            const offset_y = (height_box - height) / 2 * const_ratio;
            for (let i = 0; i < points.length; i++) {
                const x = (points[i].x - min_x) / 40 * const_ratio + offset_x;
                const y = (points[i].y - min_y) / 40 * const_ratio + offset_y;
                if (points[i].z) {
                    const canvasX = width_total * const_ratio - Math.floor(x);
                    const canvasY = Math.floor(y);
                    context.lineTo(canvasX, canvasY);

                    if (safeAreaX === null || canvasX < safeAreaX) {
                        safeAreaX = canvasX;
                    }

                    if (safeAreaWidth === null || canvasX - safeAreaX > safeAreaWidth) {
                        safeAreaWidth = canvasX - safeAreaX;
                    }

                    if (safeAreaY === null || canvasY < safeAreaY) {
                        safeAreaY = canvasY;
                    }

                    if (safeAreaHeight === null || canvasY - safeAreaY > safeAreaHeight) {
                        safeAreaHeight = canvasY - safeAreaY;
                    }
                } else {
                    const canvasX = width_total * const_ratio - Math.floor(x);
                    const canvasY = Math.floor(y);
                    context.moveTo(canvasX, canvasY);
                }
            }

            context.fillStrokeShape(shape);
        },
    });

    frameLayer.add(background);
    frameLayer.add(frame);
    frameLayer.add(delimiter);
    frameLayer.add(topArrow);
    frameLayer.add(topText);
    frameLayer.add(bottomArrow);
    frameLayer.add(plt);
};
