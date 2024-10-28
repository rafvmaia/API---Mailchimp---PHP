<?php
session_start();

$apiKey = '30244dd2a6501be4a4e2e8b270e4a56c-us10';
$audienceId = '8ce691efbe';
$datacenter = substr($apiKey, strpos($apiKey, '-') + 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];


    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $data = [
            'email_address' => $email,
            'status'        => 'subscribed',
            'merge_fields'  => [
                'FNAME' => $nome
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://$datacenter.api.mailchimp.com/3.0/lists/$audienceId/members/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: apikey ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $message = 'E-mail cadastrado com sucesso!';
        } else {
            $message = 'Erro ao cadastrar no Mailchimp: ' . json_encode($response);
        }
    } else {
        $message = 'Por favor, insira um e-mail válido.';
    }

    echo "<script>
            alert('$message');
            window.location.href = '../obrigado.html';
          </script>";
    exit();
}
?>
