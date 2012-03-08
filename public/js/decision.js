$(function(){

    window.DecisionQuiz = Backbone.View.extend({
                
        events: {
            'click .hit': 'hit',
            'click .stand': 'stand',
            'click .split': 'split',
            'click .double': 'double'
        },
        
        initialize: function() {
        
            _.bindAll(
                this,
                'render',
                'hit',
                'stand',
                'split',
                'double',
                'nextQuestion',
                'currentQuestion'
            );          
            
            this.questionIndex = 0;
            
            this.bind('next-question', this.nextQuestion);
            
        },
        
        render: function() {
			this.currentQuestion().show();
			return this;
        },
        
        hit: function() {
console.log('HIT');

			var q = this.currentQuestion();
console.log(q.find('span.playing-card'));

			//this.trigger('next-question');
        },
        
        stand: function() {
console.log('STAND');
        },
        
        split: function() {
console.log('SPLIT');
        },
        
        double: function() {
console.log('DOUBLE');
        },
        
        nextQuestion: function() {
        	
        	this.currentQuestion().hide();
        	this.questionIndex++;
        	this.render();
        	
        },
        
        currentQuestion: function() {
        	
        	return $(this.$('div.clearfix').get(this.questionIndex));
        	
        }
        
    });
 
});
