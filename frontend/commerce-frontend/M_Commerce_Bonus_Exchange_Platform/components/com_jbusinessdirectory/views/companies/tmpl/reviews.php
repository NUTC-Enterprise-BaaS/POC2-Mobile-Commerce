<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<a class="add-review-link" href="javascript:void(0)" onclick="showReviewForm()" <?php echo ($appSettings->enable_reviews_users && $user->id ==0) ? 'style="display:none"':'' ?>><?php echo JText::_("LNG_ADD_REVIEW") ?></a>
<?php require_once 'default_addreview.php'; ?>
<br/>	
<?php if(count($this->reviews)==0){ ?>
	<p><?php echo JText::_('LNG_NO_REVIEWS') ?></p>
<?php } ?>
<ul id="reviews">
<a name="reviews"></a>
	<?php foreach($this->reviews as $review){?>
		<li class="review">
			<div class="review-content">
			
				<h4><?php echo $review->subject ?></h4>
				
				<div class="review-author">
					<p class="review-by-content">
					<span class="reviewer-name"> <?php echo $review->name ?> </span>,
						<span class="review-date"><?php echo JBusinessUtil::getDateGeneralFormat($review->creationDate) ?></span>
					</p>
				</div>
			
				<div class="rating-block">
					<?php if(!empty($review->scores) && !empty($this->reviewCriterias)){ ?>
						<div class="review-rating" itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating">
								<span itemprop="ratingValue"><?php echo number_format($review->rating,1) ?></span>
							</div>
						<?php foreach($review->criteriaIds as $key=>$value){
								if(empty( $this->reviewCriterias[$value]))
									continue;
							?>
							<div class="review-criteria">
								<span class="review-criteria-name"><?php echo $this->reviewCriterias[$value]->name?></span>
								<span title="<?php echo $review->scores[$key] ?>" class="rating-review"></span>
							</div>
						<?php } ?>
					
					<?php }else{?>
						<div>
							<span title="<?php echo $review->rating ?>" class="rating-review"></span>
						</div>	
					<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="review-questions" id="<?php echo $review->id; ?>">
					<?php if(!empty($review->answerIds) && !empty($this->reviewQuestions) && !empty($this->reviewAnswers)) { ?>
						<a style="display:none" href="javascript:void(0)" id="show-questions<?php echo $review->id; ?>" onclick="showReviewQuestions('<?php echo $review->id; ?>')"><?php echo JText::_('LNG_SHOW_REVIEW_QUESTIONS'); ?>	</a>
						<div id="review-questions<?php echo $review->id; ?>">
						<?php foreach($review->questionIds as $key=>$value){
							if(!isset($this->reviewQuestions[$value]))
								continue;
							$question = $this->reviewQuestions[$value];
							$answer = $this->reviewAnswers[$review->answerIds[$value]];
						?>
						<?php if(isset($answer->answer)) { ?>
							<div class="review-question"><strong><?php echo $question->name?></strong><?php echo (isset($answer->user_id) && $user->id==$answer->user_id && $user->id!=0) ? ' <i class="dir-icon-pencil" style="cursor:pointer;" onClick="editAnswer('.$answer->id.','.$question->type.')"></i>' : ''; ?></div>
							<?php
							if($question->type == 1) {
								if ($answer->answer == 0)
									$answer->answer = JText::_('LNG_NO');
								else if ($answer->answer == 1)
									$answer->answer = JText::_('LNG_YES');
							}
							$enableEditing = (isset($answer->user_id) && $user->id==$answer->user_id && $user->id!=0) ? 'ondblclick="editAnswer('.$answer->id.','.$question->type.')"' : '';
							$editClass = (isset($answer->user_id) && $user->id==$answer->user_id && $user->id!=0) ? 'question-answer' : '';
							?>
							<?php if($question->type != 2) { ?>
								<div <?php echo $enableEditing ?> class="review-question-answer <?php echo $editClass ?>" id="question-answer<?php echo $answer->id ?>"><?php echo $answer->answer ?></div>
							<?php }
							else { ?>
								<div id="question-answer<?php echo $answer->id ?>" class="review-question-answer star-rating <?php echo $editClass ?>"></div>
								<input type="hidden" id="star-rating-score<?php echo $answer->id ?>" value="<?php echo $answer->answer ?>" />
							<?php } ?>
							<?php } ?>
						<?php } ?>
						</div>
					<?php } ?>
				</div>
				
				<div class="review-description">
					<?php echo $review->description ?>
				</div>
				<?php if(isset($review->responses) && count($review->responses)>0){ 
					foreach($review->responses as $response){
					?>
					<div class="review-response">
						<strong><?php echo JText::_('LNG_REVIEW_RESPONSE') ?></strong><br/>
						<span class="bold"><?php echo $response->firstName ?> </span>
						<p><?php echo $response->response ?></p>
					</div>
				<?php
					}
					}?>
				
				<div class="review-actions">
					<ul>
						<li class="first">
							<a href="javascript:reportReviewAbuse(<?php echo $review->id?>)"><?php echo JText::_('LNG_REPORT_ABUSE') ?></a>
						</li>
						<li>
							<a href="javascript:respondToReview(<?php echo $review->id?>)"><?php echo JText::_('LNG_RESPOND_TO_REVIEW') ?></a>
						</li>
					</ul>
				</div>
				
				<div class="rate-review">
					<span class="rate"><?php echo JText::_("LNG_RATE_REVIEW")?>:</span>
					<ul>
						<li class="thumbup"><a 
							id="increaseLike<?php echo $review->id ?>" 
							href="javascript:void(0)" onclick="increaseReviewLikeCount(<?php echo $review->id ?>)"><?php echo JText::_("LNG_THUMB_UP")?>
								</a> <span class="count"  > (<span id="like<?php echo $review->id ?>"><?php echo $review->likeCount ?></span>) </span>
						</li>
						<li class="thumbdown">
							<a  
							id="decreaseLike<?php echo $review->id ?>" 
							href="javascript:void(0)" onclick="increaseReviewDislikeCount(<?php echo $review->id ?>)"><?php echo JText::_("LNG_THUMB_DOWN")?></a> 
							<span class="count"  > (<span id="dislike<?php echo $review->id ?>"><?php echo $review->dislikeCount ?></span>) </span>
						</li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
		</li>
	<?php } ?>
</ul>
<div class="clear"></div>

<div id="report-abuse" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_REPORT_ABUSE') ?></h3>
			<div class="dialogContentBody" id="dialogContentBody">
				<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" id="reportAbuse" name="reportAbuse"  method="post">
					<p>
						<?php echo JText::_("LNG_ABUSE_INFO");?>
					</p>
					<div class="report-abuse">
						<fieldset>
							<div class="form-item">
								<label for="subject"><?php echo JText::_('LNG_EMAIL') ?></label>
								<div class="outer_input">
									<input type="text" name="email" id="email"><br>
									<span class="error_msg" id="frmEmail_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
					
							<div class="form-item">
								<label for="rating_body" ><?php echo JText::_('LNG_REPORT_ABUSE_BECAUSE')?>:</label>
								<div class="outer_input">
									<textarea rows="5" name="description" id="description" escape="false"></textarea><br>
									<span class="error_msg" id="frmDescription_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
							<?php if($this->appSettings->captcha){?>
								<div class="form-item">
									<?php 
									$namespace="jbusinessdirectory.contact";
									$class=" required";
									
									$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
																		
									if(!empty($captcha)){	
										echo $captcha->display("captcha", "captcha-div-review-abuse", $class);
									}
									
									?>
								</div>
							<?php } ?>
									
							<div class="clearfix clear-left">
								<div class="button-row ">
									<button type="button" class="ui-dir-button" onclick="saveReviewAbuse()">
											<span class="ui-button-text"><?php echo JText::_("LNG_SUBMIT")?></span>
									</button>
									<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
											<span class="ui-button-text"><?php echo JText::_("LNG_CANCEL")?></span>
									</button>
								</div>
							</div>
						</fieldset>
					</div>
					<input type='hidden' name='task' value='companies.reportAbuse'/>
					<input type='hidden' name='view' value='companies' />
					<input type="hidden" id="reviewId" name="reviewId" value="" />
					<input type="hidden" name="companyId" value="<?php echo $this->company->id?>" />
				</form>
			</div>
		</div>
	</div>
</div>


<div id="new-review-response" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_RESPOND_REVIEW') ?></h3>
			<div class="dialogContentBody" id="dialogContentBody">
				<form id="reviewResponseFrm" name ="reviewResponseFrm" action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory') ?>" method="post">
					<p>
						<?php echo JText::_('LNG_RESPOND_REVIEW_INFO') ?>
					</p>
					<div class="review-repsonse">
						<fieldset>
		
							<div class="form-item">
								<label for="firstName"><?php echo JText::_('LNG_FIRST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="firstName" id="firstName"><br>
									<span class="error_msg" id="frmFirstName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
							<div class="form-item">
								<label for="lastName"><?php echo JText::_('LNG_LAST_NAME') ?></label>
								<div class="outer_input">
									<input type="text" name="lastName" id="lastName"><br>
									<span class="error_msg" id="frmLastName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
		
		
							<div class="form-item">
								<label for="email"><?php echo JText::_('LNG_EMAIL_ADDRESS') ?></label>
								<div class="outer_input">
									<input type="text" name="email" id="email"><br>
									<span class="error_msg" id="frmEmail_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
					
							<div class="form-item">
								<label for="response" ><?php echo JText::_('LNG_REVIEW_RESPONSE')?>:</label>
								<div class="outer_input">
									<textarea rows="5" name="response" id="response" escape="false"></textarea><br>
									<span class="error_msg" id="frmDescription_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
							
							<?php if($this->appSettings->captcha){?>
								<div class="form-item">
									<?php 
										$namespace="jbusinessdirectory.contact";
										$class=" required";
										
										$captcha = JCaptcha::getInstance("recaptcha", array('namespace' => $namespace));
																			
										if(!empty($captcha)){	
											echo $captcha->display("captcha", "captcha-div-review-response", $class);
										}
									?>
								</div>
							<?php } ?>
		
							<div class="clearfix clear-left">
								<div class="button-row ">
									<button type="button" class="ui-dir-button" onclick="saveReviewResponse()">
											<span class="ui-button-text"><?php echo JText::_("LNG_SUBMIT")?></span>
									</button>
									<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="jQuery.unblockUI()">
											<span class="ui-button-text"><?php echo JText::_("LNG_CANCEL")?></span>
									</button>
								</div>
							</div>
						</fieldset>
					</div>
			
					<input type='hidden' name='task' value='companies.saveReviewResponse'/>
					<input type='hidden' name='view' value='companies' />
					<input type="hidden" id="reviewId" name="reviewId" value="" />
					<input type="hidden" name="companyId" value="<?php echo $this->company->id?>" />
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	<?php if(count($this->reviewAnswers) > 0) { ?>
	jQuery(document).ready(function(){
		jQuery('.review-questions').each(function(){
			showReviewQuestions(jQuery(this).attr('id'));
		});
	});
	<?php } ?>

	var siteRoot = '<?php echo JURI::root(); ?>';
	var url = siteRoot+'index.php?option=com_jbusinessdirectory';

	function showReviewQuestions(reviewId){
		var maxLength = 100;
		jQuery("#show-questions"+reviewId).text('<?php echo JText::_('LNG_HIDE_REVIEW_QUESTIONS'); ?>');
		jQuery("#show-questions"+reviewId).attr('onclick', 'hideReviewQuestions("'+reviewId+'")');
		jQuery("#review-questions"+reviewId).slideDown(500);
		jQuery('#review-questions'+reviewId).children('.review-question-answer').each(function(){
			if(jQuery(this).hasClass('star-rating'))
				showStarRating(jQuery(this).attr('id'));
			else
				jQuery(this).html(truncate(jQuery(this).text(), jQuery(this).attr('id'), maxLength));

		});
	}

	function hideReviewQuestions(reviewId){
		jQuery("#show-questions"+reviewId).text('<?php echo JText::_('LNG_SHOW_REVIEW_QUESTIONS'); ?>');
		jQuery("#show-questions"+reviewId).attr('onclick', 'showReviewQuestions("'+reviewId+'")');
		jQuery("#review-questions"+reviewId).slideUp(500);
	}

	function showStarRating(answerId){
		var id = answerId.slice(15, answerId.length);
		jQuery('#'+answerId).empty();
		jQuery('#'+answerId).raty({
			number: 10,
			half:       true,
			precision:  false,
			size:       24,
			readOnly: true,
			starHalf:   'star-half.png',
			starOff:    'star-off.png',
			starOn:     'star-on.png',
			start: jQuery('#star-rating-score'+id).val(),
			noRatedMsg: "<?php echo JText::_('LNG_NOT_RATED_YET') ?>",
			path: '<?php echo COMPONENT_IMAGE_PATH?>'
		});
	}


	function editAnswer(answerId, answerType){
		var answerDiv = jQuery("#question-answer"+answerId);
		var answer = answerDiv.text();
		var data;
		var score;

		if(answerType == 0) {
			showFullText(answerId);
			answer = answerDiv.text();
			data = '<textarea style="width:100%;" name="answer-' + answerId + '" id="answer-' + answerId + '" onblur="saveAnswer(\'' + answerId + '\', \'' + answerType + '\')" >' + answer + '</textarea>';
		}
		else if(answerType == 1) {
			var yes = answer == "<?php echo JText::_('LNG_YES') ?>" ? 'checked="checked"' : "";
			var no = answer == "<?php echo JText::_('LNG_NO') ?>" ? 'checked="checked"' : "";
			data = '<input type="radio" id="answer-'+answerId+'" value="1" onclick="saveAnswer(\''+ answerId +'\', \''+ answerType +'\')" name="answer-' + answerId + '"' + yes + '> <?php echo JText::_('LNG_YES') ?></input>';
			data += ' <input type="radio" id="answer-'+answerId+'" value="0" onclick="saveAnswer(\''+ answerId +'\', \''+ answerType +'\')" name="answer-' + answerId + '"' + no + '> <?php echo JText::_('LNG_NO') ?></input>';
		}
		else if(answerType == 2){
			data = '<div class="rating-answer"></div>';
			score = parseFloat(answer);
		}
		jQuery("#question-answer"+answerId).attr('class', '');
		answerDiv.html(data);

		if(answerType == 2){
			jQuery('.rating-answer').raty({
				number: 10,
				half:       true,
				precision:  false,
				size:       24,
				starHalf:   'star-half.png',
				starOff:    'star-off.png',
				starOn:     'star-on.png',
				noRatedMsg: "<?php echo JText::_('LNG_NOT_RATED_YET') ?>",
				click:	function(score) {
					jQuery(this).parent().children("input").val(score);
					document.getElementById('star-rating-score'+answerId).value = score;
					saveAnswer(answerId, answerType);
				},
				start:	 score,
				path: '<?php echo COMPONENT_IMAGE_PATH?>'
			});
		}
	}

	function saveAnswer(answerId, answerType){
		var data;
		if(answerType == 0)
			data = jQuery("#answer-"+answerId).val();
		else if(answerType == 1)
			data = jQuery("input[name='answer-"+answerId+"']:checked").val();
		else if(answerType == 2)
			data = jQuery("#star-rating-score"+answerId).val();

		var urlSaveAnswerAjax = url+'&task=companies.saveAnswerAjax';
		jQuery.ajax({
			type: "POST",
			url: urlSaveAnswerAjax,
			data: { answer: data, answerId: answerId },
			dataType: 'json',
			success: function(){
				jQuery("#question-answer"+answerId).empty();
				if(answerType==1) {
					if (data == 0)
						data = "<?php echo JText::_('LNG_NO') ?>";
					else if (data == 1)
						data = "<?php echo JText::_('LNG_YES') ?>";
				}
				if(answerType != 2)
					jQuery("#question-answer"+answerId).text(data);
				else {
					showStarRating('question-answer' + answerId);
				}
			}
		});
		if(answerType != 2)
			jQuery("#question-answer"+answerId).attr('class', 'answer question-answer');
		else
			jQuery("#question-answer"+answerId).attr('class', 'answer star-rating');
	}

	function truncate(text, id, limit) {
		var truncatedText;

		if (id.length > 10)
			id = id.slice(15, id.length);

		if (text.length <= limit){
			return text;
		}
		else if (text.length > limit) {
			truncatedText = text.slice(0, limit) + '<span>...</span>';
			truncatedText += '<a href="javascript:void(0)" onClick=\'showFullText("' + id + '")\' class="more" id="more' + id + '"><?php echo JText::_('LNG_READ_MORE'); ?></a>';
			truncatedText += '<span style="display:none;" id="more-text">' + text.slice(limit, text.length) + '</span>';

			return truncatedText;
		}
	}


	function showFullText(id){
		jQuery('#more'+id).next().show();
		jQuery('#more'+id).prev().remove();
		jQuery('#more'+id).remove();
	}
</script>