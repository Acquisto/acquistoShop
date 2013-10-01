<div class="comment_default<?php echo $this->class; ?>" id="<?php echo $this->id; ?>">
<p class="info"><?php echo $this->by; ?> <?php if ($this->website): ?><a href="<?php echo $this->website; ?>" rel="nofollow" target="_blank"><?php endif; ?><?php echo $this->name; ?><?php if ($this->website): ?></a><?php endif; ?> | <time datetime="<?php echo $this->datetime; ?>" class="date"><?php echo $this->date; ?></time></p>
<div class="comment">
<div class="image_container" style="float: left; margin-right: 15px;">
    <a href="http://www.gravatar.com/<?php echo md5(strtolower(trim($this->email))); ?>"><img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($this->email))); ?>?s=50" alt="" border="0" width="50" height="50"></a>
</div>
<?php echo $this->comment; ?>
</div>
<?php if ($this->addReply): ?>
<div class="reply">
<p class="info"><?php echo $this->rby; ?> <?php echo $this->authorName; ?></p>
<div class="comment">
<?php echo $this->reply; ?>
</div>
</div>
<?php endif; ?>
<div class="clear"></div>
</div>