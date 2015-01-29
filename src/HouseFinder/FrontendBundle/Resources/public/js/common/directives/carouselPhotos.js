angular.module('app').directive('appCarouselphotos', ['$filter', function($filter){
    return {
        restrict: 'E',
        replace: true,
        scope: {
            fi: '=imageWH'
        },
        templateUrl: 'app/directives/carouselPhotos.html',
        controller: ['$scope', function($scope){
            $scope.isPhotoActive = function(index) {
                return $scope.photosOrigin[index] === $scope.photo;
            };
            $scope.isThumbnailActive = function (index) {
                return $scope.photosSmall[index].indexOf($scope.photoSmall) > -1;
            };
            $scope.isThumbnailPhotoActive = function (photo) {
                return photo === $scope.photoSmall;
            };
            $scope.changeSmallActive = function(index, direction) {
                var smallItems = $($scope.element).find('.thumbnail-item');
                smallItems.removeClass('active');
                $(smallItems[index]).addClass('active');
                if (direction && $('#carousel-small .item.active').index() != Math.floor(index/$scope.smallInItem)) {
                    $('#carousel-small').carousel(direction);
                }
            };
            $scope.changeActive = function($event) {
                var target = $($event.currentTarget).closest('.carousel'),
                    index = $($event.currentTarget).index(),
                    parentIndex = $('#carousel-small .item.active').index(),
                    newIndex = $scope.smallInItem*parentIndex + index;
                $scope.changeSmallActive(index);
                $('#carousel-main').carousel(newIndex);
            };
            $scope.carouselThumbnailPrev = function($event) {
                var target = $($event.currentTarget).closest('.carousel'),
                    parentIndex = $(target).find('.item.active').index() - 1;
                if (parentIndex < 0) {
                    parentIndex = $scope.photosSmall.length - 1;
                }
                var newIndex = $scope.smallInItem*parentIndex,
                    smallItems = $($scope.element).find('.thumbnail-item');
                $(target).carousel('prev');
                smallItems.removeClass('active');
                $(smallItems[newIndex]).addClass('active');
                $('#carousel-main').data('direction', 'prev');
                $('#carousel-main').carousel(newIndex);
                return true;
            };
            $scope.carouselThumbnailNext = function($event) {
                var target = $($event.currentTarget).closest('.carousel'),
                    parentIndex = $(target).find('.item.active').index() + 1;
                if (parentIndex >= $scope.photosSmall.length) {
                    parentIndex = 0;
                }
                var newIndex = $scope.smallInItem*parentIndex,
                    smallItems = $($scope.element).find('.thumbnail-item');
                $(target).carousel('next');
                smallItems.removeClass('active');
                $(smallItems[newIndex]).addClass('active');
                $('#carousel-main').data('direction', 'next');
                $('#carousel-main').carousel(newIndex);
                return true;
            };
            $scope.carouselPrev = function($event) {
                var target = $($event.currentTarget).closest('.carousel');
                $(target).carousel('prev');
                var index = $(target).find('div.active').index() - 1;
                if (index < 0) {
                    index = $scope.photosOrigin.length - 1;
                }
                $scope.changeSmallActive(index, 'prev');
            };
            $scope.carouselNext = function($event) {
                var target = $($event.currentTarget).closest('.carousel');
                $(target).carousel('next');
                var index = $(target).find('div.active').index() + 1;
                if (index === $scope.photosOrigin.length) {
                    index = 0;
                }
                $scope.changeSmallActive(index, 'next');
            };
        }],
        link: function (scope, element, attrs) {
            scope.element = element;
            scope.smallWidth = attrs.smallwidth;
            scope.photo = attrs.photo;
            scope.photosOrigin = JSON.parse(attrs.photos);
            var w = element.width();
            scope.smallInItem = Math.floor(w / scope.smallWidth);
            scope.smallClass = Math.floor(12 / scope.smallInItem);
            scope.photoSmall = $filter('imageWH')(scope.photo, scope.smallWidth, scope.smallWidth);
            scope.photos = [];
            scope.photosSmall = [];
            var j = 0;
            for (var i = 0; i < scope.photosOrigin.length; i++) {
                scope.photos.push($filter('imageWH')(scope.photosOrigin[i], w, w));
                if (!scope.photosSmall[j]) scope.photosSmall[j] = [];
                scope.photosSmall[j].push($filter('imageWH')(scope.photosOrigin[i], scope.smallWidth, scope.smallWidth));
                if (scope.photosSmall[j].length === scope.smallInItem) {
                    j++;
                }
            }
            $.fn.carousel.Constructor.prototype.to = function (pos) {
                var that = this
                var activeIndex = this.getItemIndex(this.$active = this.$element.find('.item.active'))
                if (pos > (this.$items.length - 1) || pos < 0) return

                if (this.sliding)       return this.$element.one('slid.bs.carousel', function () {
                    that.to(pos)
                }) // yes, "slid"
                if (activeIndex == pos) return this.pause().cycle()
                var direction = pos > activeIndex ? 'next' : 'prev',
                    dataDirection = this.$element.data('direction');
                if (dataDirection) {
                    direction = dataDirection;
                    this.$element.removeData('direction');
                }
                return this.slide(direction, this.$items.eq(pos))
            }
        }
    }
}]);