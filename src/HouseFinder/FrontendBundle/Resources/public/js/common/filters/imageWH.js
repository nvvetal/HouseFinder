angular.module('app').filter('imageWH', function() {
    return function(image, w, h) {
        return image.replace(/WIDTH/, w).replace(/HEIGHT/, h);
    };
});