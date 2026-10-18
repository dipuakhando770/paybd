<?php
$item_link_detail = user_url("tickets/view/" . $item['ids']);
switch ($item['status']) {
    case 'closed':
        $class_item_status = 'btn-success';
        break;

    case 'pending':
        $class_item_status = 'btn-warning';
        break;

    case 'answered':
        $class_item_status = 'btn-primary';
        break;

    default:
        $class_item_status = 'btn-info';
        break;
}
$xhtml_item_status = sprintf(
    '<span class="btn %s btn-sm">
            <small>%s</small>
        </span>',
    $class_item_status,
    ticket_status_title($item['status'])
);

$class_subject = ($item['status'] == "closed") ? "text-muted" : "";
$xhtml_item_subject = "#" . $item['ids'] . " - " . $item['subject'] . ' ';
if ($item['is_user_read'] == 0) {
    $xhtml_item_subject .= '<span class="badge badge-warning">' . lang("Unread") . '</span>';
}
$xhtml_item_subject_content = sprintf(
    '<div class="content">
            <div class="subject %s">
                %s
            </div>
            <div class="time">
                <small>%s</small>
            </div>
        </div>',
    $class_subject,
    $xhtml_item_subject,
    $item['updated_at']
);

?>

<div class="mb-2 item tr_<?= $item['ids'] ?>">
    <a href="<?php echo $item_link_detail; ?>" class="p-l-5 d-flex text-decoration-none">
        <?php echo $xhtml_item_subject_content; ?>
    </a>
    <span class="action">
        <?php echo $xhtml_item_status; ?>
    </span>
    <div class="clearfix"></div>
</div>