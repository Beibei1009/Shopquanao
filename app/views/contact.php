<?php ob_start(); ?>

<style>
.contact-container {
    max-width: 500px;
    margin: 40px auto;
    background: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.contact-container h2 {
    text-align: center;
    color: #d32f2f;
    margin-bottom: 20px;
}

.contact-container label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #333;
}

.contact-container input,
.contact-container textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 15px;
    font-family: Arial, sans-serif;
}

.contact-container textarea {
    height: 100px;
    resize: none;
}

.contact-container button {
    display: block;
    width: 100%;
    background: #d32f2f;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.contact-container button:hover {
    background: #b71c1c;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

footer {
    margin-top: 40px;
}

/* Popup cảm ơn */
#popupMessage {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 25px 45px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    text-align: center;
    z-index: 9999;
    opacity: 0;
    transition: all 0.4s ease;
    font-family: Arial, sans-serif;
    animation: fadeIn 0.5s ease;
}

#popupMessage h3 {
    color: #2e7d32;
    margin-bottom: 8px;
    font-size: 20px;
}

#popupMessage p {
    color: #444;
    font-size: 15px;
}
</style>

<div class="contact-container">
    <h2>Liên hệ</h2>
    <form action="" method="post">
        <label for="name">Họ tên:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Nội dung:</label>
        <textarea id="message" name="message" required></textarea>

        <button type="submit">Gửi liên hệ</button>
    </form>
</div>

<?php
// Nếu người dùng gửi form thì hiển thị popup cảm ơn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
   echo "
<div id='overlay'></div>
<div id='popupMessage'>
    <h3>❤️ Cảm ơn $name!</h3>
    <p>Chúng tôi sẽ phản hồi sớm qua email:<br><strong>$email</strong></p>
</div>";
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const popup = document.getElementById("popupMessage");
    if (popup) {
        popup.style.opacity = "1";
        setTimeout(() => {
            popup.style.opacity = "0";
            setTimeout(() => popup.style.display = "none", 500);
        }, 2500);
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';