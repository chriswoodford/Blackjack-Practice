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
                'action',
                'checkAnswer',
                'nextQuestion',
                'currentQuestion',
                'correctAnswer',
                'incorrectAnswer'
            );          
            
            this.options.questionIndex = 0;
            
            this.bind('next-question', this.nextQuestion);
            this.bind('correct-answer', this.correctAnswer);
            this.bind('incorrect-answer', this.incorrectAnswer);
            
        },
        
        render: function() {
			this.currentQuestion().show();
			return this;
        },
        
        hit: function() {
			this.action('H');
        },
        
        stand: function() {
			this.action('S');
        },
        
        split: function() {
			this.action('P');
        },
        
        double: function() {
			this.action('D');
        },
        
        action: function(action) {

        	this.options.currentAction = action;
        	
			var q = this.currentQuestion();
			var cards = q.find('span.playing-card');
			var hand = [
			    this.parseCard($(cards.get(0))), 
			    this.parseCard($(cards.get(1)))
			];
			var dealer = this.parseCard($(cards.get(2)));

			$.get(this.options.decisionUrl, {
				strategy: this.options.decisionStrategy,
				hand: hand.join(','),
				dealer: dealer
			}, this.checkAnswer, 'json');

        },
        
        checkAnswer: function(data) {

        	if (data.decision == this.options.currentAction) {
        		this.trigger('correct-answer');
        	} else {
				this.trigger('incorrect-answer');
        	}

        },
        
        correctAnswer: function() {
console.log('CORRECT');
			this.trigger('next-question');
        },
        
        incorrectAnswer: function() {
console.log('INCORRECT');
        	this.trigger('next-question');
        },
        
        nextQuestion: function() {

			this.currentQuestion().hide();
			this.options.questionIndex++;
			this.render();

        },
        
        currentQuestion: function() {

        	return $(this.$('div.clearfix').get(this.options.questionIndex));

        },
        
        parseCard: function(elt) {
        	
        	matches = elt.attr('class')
        		.match(/(hearts|spades|clubs|diamonds)-(.)/);
			return matches[2];
			
        }
        
    });
 
});
