if (document.body.hasAttribute("data-loading")) {
    var imgLoad = imagesLoaded('html', {background: '*'});
    var timing = {duration: 1800, fill: 'forwards', easing: 'ease-out'};

    imgLoad.on('progress', onProgress);
    imgLoad.on('always', onAlways);

    function onProgress(imgLoad, image) {
        /* var result = image.isLoaded ? 'loaded ' : 'failed loading ';
         console.log(result +  image.img.src); */
    }

    function onAlways() {
        console.log("%cAll images have been loaded", 'background: #303030; color: #f90; padding:2px 8px;');
        document.body.removeAttribute("data-loading", "");

        document.getElementsByTagName("html")[0].animate([
            {filter: 'brightness(0%)'},
            {filter: 'brightness(0%)', offset: 0.8},
            {filter: 'brightness(100%)'}
        ], timing);
    }
}