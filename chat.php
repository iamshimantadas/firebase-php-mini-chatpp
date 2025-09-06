<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$myUser = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Chat</title>
</head>
<body>
  <h2>Welcome, <?php echo $myUser; ?></h2>

  <input type="text" id="toUser" placeholder="Send To (username)">
  <div id="messages" style="border:1px solid #ccc;height:300px;overflow:auto;"></div>
  <input type="text" id="messageInput" placeholder="Type message...">
  <button id="sendBtn">Send</button>

<script type="module">
 import { initializeApp } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-app.js";
  import { getDatabase, ref, push, onChildAdded } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-database.js";

const firebaseConfig = {
  apiKey: ,
  authDomain: ,
  databaseURL: ,
  projectId: ,
  storageBucket: ,
  messagingSenderId: ,
  appId: 
};

  const app = initializeApp(firebaseConfig);
  const db = getDatabase(app);
  const currentUser = "<?php echo $myUser; ?>";

  function sendMessage() {
    const toUser = document.getElementById("toUser").value.trim();
    const msg = document.getElementById("messageInput").value.trim();

    if (msg && toUser) {
      const chatId = currentUser < toUser ? `${currentUser}_${toUser}` : `${toUser}_${currentUser}`;
      const chatRef = ref(db, "chats/" + chatId);

      push(chatRef, {
        sender: currentUser,
        receiver: toUser,
        message: msg,
        timestamp: Date.now()
      });

      document.getElementById("messageInput").value = "";
    }
  }

  function loadMessages() {
    const toUser = document.getElementById("toUser").value.trim();
    if (!toUser) return;

    const chatId = currentUser < toUser ? `${currentUser}_${toUser}` : `${toUser}_${currentUser}`;
    const messagesDiv = document.getElementById("messages");
    messagesDiv.innerHTML = "";

    const chatRef = ref(db, "chats/" + chatId);

    onChildAdded(chatRef, (snapshot) => {
      const data = snapshot.val();
      const msg = document.createElement("div");
      msg.textContent = data.sender + ": " + data.message;
      messagesDiv.appendChild(msg);
      messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
  }

  document.getElementById("sendBtn").addEventListener("click", sendMessage);
  document.getElementById("toUser").addEventListener("change", loadMessages);

</script>
</body>
</html>
