<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Possible questions');?></label>
    <textarea rows="5" cols="" class="form-control" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Each question has to be to separate line');?>" name="question"><?php echo htmlspecialchars($question->question)?></textarea>    
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Answer');?></label>
    <input type="text" class="form-control" name="answer"  value="<?php echo htmlspecialchars($question->answer)?>" />
</div>