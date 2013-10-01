<div class="<?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php if(is_array($this->Breadcrumb)): foreach($this->Breadcrumb as $item): ?>
<?php if($item['url']): ?>
<a title="<?php echo $item['title']; ?>" href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a> &gt;
<?php else: ?>
<span class="active"><?php echo $item['title']; ?></span>
<?php endif; ?>
<?php endforeach; endif; ?>
</div>