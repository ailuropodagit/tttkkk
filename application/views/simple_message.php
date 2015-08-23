<div id="success-message">
    <div id="success-message-title">
        <?php echo $simple_info['title']; ?>
    </div>
    <div id="success-message-subtitle">
        <?php echo $simple_info['sentence1']; ?>
    </div>
    <div id="success-message-paragraph">
        <?php echo $simple_info['sentence2']; ?>
    </div>
    <div id="success-message-link">
        <?php echo anchor($simple_info['back_page_url'], $simple_info['back_page']); ?>
    </div>
</div>
