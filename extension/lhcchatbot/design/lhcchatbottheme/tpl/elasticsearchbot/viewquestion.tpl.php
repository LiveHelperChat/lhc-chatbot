<h1>View question</h1>

<div class="form-group">
    <label>View question, base on <a href="#" onclick="lhc.previewChat(<?php echo $question->chat_id?>)"><i class="material-icons">info_outline</i></a> <?php  echo $question->chat_id?> chat</label>
    <input type="text" class="form-control" name="question" value="<?php echo htmlspecialchars($question->question)?>" />
</div>

<ul class="list-unstyled">
    <?php foreach ($items as $item) : ?>
        <li><a title="<?php echo $question->chat_id?>" href="#" onclick="lhc.previewChat(<?php echo $question->chat_id?>)"><i class="material-icons">info_outline</i></a> [<?php echo $item->match_count?>] - <?php echo htmlspecialchars($item->answer)?></li>
    <?php endforeach; ?>
</ul>
