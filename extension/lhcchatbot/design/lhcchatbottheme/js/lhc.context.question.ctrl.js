lhcAppControllers.controller('ContextQuestionFormCtrl',['$scope','$http','$location','$rootScope', '$log', function($scope, $http, $location, $rootScope, $log) {

    this.answers = [];
    this.size = 6;
    this.fieldtype = 'text';
    this.visibility = 'all';
    this.showcondition = 'always';

    var that = this;

    this.move = function(element, offset) {
        index = that.answers.indexOf(element);
        newIndex = index + offset;
        if (newIndex > -1 && newIndex < that.answers.length){
            removedElement = that.answers.splice(index, 1)[0];
            that.answers.splice(newIndex, 0, removedElement)
        }
    };

    this.addField = function() {
        that.answers.push({
            'answer' : '',
            'context' : 0,
            'id' : 0
        });
    };

    this.deleteField = function(field) {
        that.answers.splice(that.answers.indexOf(field),1);
    };

    this.moveLeftField = function(field) {
        that.move(field,-1);
    }

    this.moveRightField = function(field) {
        that.move(field,1);
    }


}]);