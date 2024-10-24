<?php
session_start();

// CSRFトークンの生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// フォームが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRFトークンの確認
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF攻撃が検出されました。");
    }

    // 入力データのサニタイズ
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // メールアドレスの形式をバリデーション
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("無効なメールアドレスです。");
    }

    // メールヘッダインジェクションの防止
    if (preg_match("/[\r\n]/", $email) || preg_match("/[\r\n]/", $subject)) {
        die("不正な入力が検出されました。");
    }

    // メール送信
    $to = "h.magota.revai@gmail.com";
    $headers = "From: " . $email . "\r\n" .
               "Reply-To: " . $email . "\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "お名前: $name\n";
    $body .= "メールアドレス: $email\n";
    $body .= "件名: $subject\n";
    $body .= "お問い合わせ内容:\n$message\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "メールが正常に送信されました。";
    } else {
        echo "メール送信に失敗しました。";
    }

    // CSRFトークンのリセット
    unset($_SESSION['csrf_token']);
}
?>
