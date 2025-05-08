<?php

$conn = new mysqli('localhost', 'root', '', 'bh');
$tenants_id = isset($_GET['tenants_id']) ? intval($_GET['tenants_id']) : 0;
$display_sms = mysqli_query($conn, "SELECT inbox.*, tenants.*
FROM inbox 
INNER JOIN tenants ON inbox.tenants_id = tenants.tenants_id 
WHERE tenants.tenants_id = $tenants_id");

while ($result = mysqli_fetch_array($display_sms)) {
  extract($result);
  $isAdmin = ($admin_id == 1);
  if ($isAdmin) {
?>
    <div class="direct-chat-msg">
      <div class="direct-chat-infos clearfix">
        <span class="direct-chat-name float-left">Admin Name</span>
      </div>
      <img class="direct-chat-img" src="../dist/img/user1-128x128.jpg" alt="message user image">
      <?php if (!empty($content)) : ?>
        <div class="direct-chat-text">
          <?php echo htmlspecialchars($content); ?>
        </div>
        <span class="direct-chat-timestamp"><?php echo date('D h:i A', strtotime($date)); ?></span>
      <?php endif; ?>
      <?php if (!empty($attachment)) : ?>
        <div class="attachment" style="margin: 5px 0 0 50px;">
          <a href="../image/<?php echo htmlspecialchars($attachment); ?>" class="gallery-item"> <img src="../image/<?php echo htmlspecialchars($attachment); ?>" alt="Attachment" style="max-width: 100%;"></a>
        </div>
        <span class="direct-chat-timestamp"><?php echo date('D h:i A', strtotime($date)); ?></span>
      <?php endif; ?>
    </div>
  <?php
  } else {
  ?>
    <div class="direct-chat-msg right">
      <div class="direct-chat-infos clearfix">
        <span class="direct-chat-name float-right"><?php echo htmlspecialchars($name); ?></span>
        <span class="direct-chat-timestamp float-left"><?php echo date('D h:i A', strtotime($date)); ?></span>
      </div>
      <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
      <?php if (!empty($content)) : ?>
        <div class="direct-chat-text">
          <?php echo htmlspecialchars($content); ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($attachment)) : ?>
        <div class="attachment" style="margin-top: 10px;">
          <img src="../image/<?php echo htmlspecialchars($attachment); ?>" alt="Attachment" style="max-width: 100%;">
        </div>
      <?php endif; ?>
    </div>
<?php
  }
}
?>
</div>