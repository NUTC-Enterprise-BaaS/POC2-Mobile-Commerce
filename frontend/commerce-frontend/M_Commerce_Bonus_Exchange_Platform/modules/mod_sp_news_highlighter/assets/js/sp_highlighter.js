/*
# SP News Highlighter Module by JoomShaper.com
# --------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2014 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/

(function($){

    $.fn.spNewsHighlighter = function(options) {  

        var settings = {
            'interval': 5000,
            'fxduration': 1000,
            'animation': 'slide-vertical'
        };

        this.each(function() {        

            if (options) { 
                $.extend(settings, options);
            }

            var wrapper = this;
            
            var SPNewsHighlighter = function(){

                this.items      = $(wrapper).find('.sp-nh-item').find('>div');
                this.count      = (this.items.length) - 1;
                this.navPrev    = $(wrapper).find('.sp-nh-prev');
                this.navNext    = $(wrapper).find('.sp-nh-next');
                this.sliding    = false;

                this.getCurrentIndex = function(){
                    return this.items.filter('.current').index();
                };

                this.go = function(index, direction){

                    if(this.sliding === false)
                    {
                        this.sliding        = true;
                        var that            = this;
                        this.previous       = this.items.eq(this.getCurrentIndex());
                        this.current        = this.items.eq(index);


                        if (settings.animation == 'fade') {
                            this.previous.removeClass('current').css('display', 'none');
                            this.current.fadeIn(settings.fxduration, function(){
                                that.sliding = false;
                            }).addClass('current');
                        } else if (settings.animation == 'slide-horizontal') {

                            this.previous.css({
                                'display': 'block',
                                'position': 'absolute',
                                'overflow': 'hidden',
                                'top': 0,
                                'left': 0,
                                'width': $(wrapper).outerWidth(),
                                'height': this.previous.outerHeight()
                            }).animate({
                                'left': (direction == 'left') ? -$(wrapper).outerWidth() : $(wrapper).outerWidth()
                            }, settings.fxduration, function(){
                                $(this).removeAttr('style').css('display', 'none').removeClass('current')
                                that.sliding = false;
                            });

                            this.current.css({
                                'display': 'block',
                                'position': 'absolute',
                                'overflow': 'hidden',
                                'top': 0,
                                'left': ( direction == 'left' ) ? $(wrapper).outerWidth() : -$(wrapper).outerWidth(),
                                'width': $(wrapper).outerWidth(),
                                'height': this.current.outerHeight()
                            }).animate({
                                'left': 0
                            }, settings.fxduration, function(){
                                $(this).removeAttr('style').css('display', 'block').addClass('current')
                                $(wrapper).removeAttr('style')
                                that.sliding = false;
                            });

                        } else if (settings.animation == 'slide-vertical') {

                            this.previous.css({
                                'display': 'block',
                                'position': 'absolute',
                                'overflow': 'hidden',
                                'top': 0,
                                'left': 0,
                                'width': $(wrapper).outerWidth(),
                                'height': this.previous.outerHeight()
                            }).animate({
                                'top': ( direction == 'left' ) ? -this.previous.outerHeight() : this.previous.outerHeight()
                            }, settings.fxduration, function(){
                                $(this).removeAttr('style').css('display', 'none').removeClass('current')
                                that.sliding = false;
                            });

                            this.current.css({
                                'display': 'block',
                                'position': 'absolute',
                                'overflow': 'hidden',
                                'top': ( direction == 'left' ) ? this.current.outerHeight() : -this.current.outerHeight(),
                                'left': 0,
                                'width': $(wrapper).outerWidth(),
                                'height': this.current.outerHeight()
                            }).animate({
                                'top': 0
                            }, settings.fxduration, function(){
                                $(this).removeAttr('style').css('display', 'block').addClass('current')
                                $(wrapper).removeAttr('style')
                                that.sliding = false;
                            });

                        } else {
                            this.previous.css('display', 'none').removeClass('current');
                            this.current.css('display', 'block').addClass('current');
                            that.sliding = false;
                        }

                    }

                };

                this.next = function(){
                    var index = this.getCurrentIndex();
                    if (index < this.count) {
                        this.go(index + 1, 'left');
                    } else {
                        this.go(0, 'left');
                    }
                };

                this.prev = function(){
                    var index = this.getCurrentIndex();
                    if (index > 0) {
                        this.go(index - 1, 'right');
                    } else {
                        this.go(this.count, 'right');
                    }   
                };  

                this.init = function(){
                    this.items.hide().first().addClass('current').show();
                };

            };

            var spnewshighlighter = new SPNewsHighlighter();
            spnewshighlighter.init();

            var timer = function(){
                spnewshighlighter.next();
            };
            
        setInterval(timer, settings.interval);//Autoplay

        spnewshighlighter.navNext.on('click', function(e){
            e.preventDefault();
            spnewshighlighter.next();   
        });
        spnewshighlighter.navPrev.on('click', function(e){
            e.preventDefault();
            spnewshighlighter.prev();
        });

    });
};
})(jQuery);


