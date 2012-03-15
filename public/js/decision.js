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
                'incorrectAnswer',
                'updateScore'
            );          
            
            this.options.questionIndex = 0;
            this.options.correctResponses = 0;
            
            this.bind('next-question', this.nextQuestion);
            this.bind('correct-answer', this.correctAnswer);
            this.bind('incorrect-answer', this.incorrectAnswer);
            this.bind('update-score', this.updateScore);
            
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
			var cards = q.find('span.playing-card').toArray();
			var dealer = this.parseCard($(cards.pop()));
			var hand = [];
			var parser = this;
			
			$.each(cards, function(i, card) {				
				hand.push(parser.parseCard($(card)));
			});
			
			$.get(this.options.decisionUrl, {
				strategy: this.options.decisionStrategy,
				hand: hand.join(','),
				dealer: dealer
			}, this.checkAnswer, 'json');

        },
        
        checkAnswer: function(data) {

        	this.options.currentAnswer = data.decision;
        	
        	if (data.decision == this.options.currentAction) {
        		this.trigger('correct-answer');
        	} else {
				this.trigger('incorrect-answer');
        	}

        },
        
        correctAnswer: function() {

        	this.options.correctResponses++;
        	
        	var question = this;
			var q = this.currentQuestion();
			
			q.empty()
				.css('background', 'green')
				.css('height', 70)
				.css('padding-top', 35)
				.html('<h1 style="color:white;">CORRECT</h1>');

			q.fadeOut(2000, function() {
				question.trigger('next-question');
			});
			
        },
        
        incorrectAnswer: function() {

        	var question = this;
			var q = this.currentQuestion();
			q.empty()
				.css('background', 'red')
				.css('height', 85)
				.css('padding-top', 20);
			
			q.append('<h1 style="color:white;">INCORRECT</h1>')
				.append('<h3>CORRECT: ' + this.options.currentAnswer + '</h3>');

			q.fadeOut(2500, function() {
				question.trigger('next-question');
			});
		
        },
        
        nextQuestion: function() {
        	
			this.currentQuestion().hide();
			this.options.questionIndex++;
			this.trigger('update-score');
			this.render();
			
        },
        
        updateScore: function() {
        
        	this.$('.score .total').text(this.options.questionIndex);
    		this.$('.score .correct').text(this.options.correctResponses);
        	
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
