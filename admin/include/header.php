<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Wisteria Homes: CMS</title>
    <meta name="description" content="Administrator View for Wisteria Homes">
    <meta name="keywords" content="cms, wisteria, homes, real-estate, realty, nz, new zealand, buy, homes, house, houses, purchase, real, estate">
    <meta name="author" content="Hannah Davydova">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato|Libre+Baskerville&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- My CSS -->
    <link rel="stylesheet" href="../admin/style/cms_style.css">
</head>

<body>

    <div class="wrapper">

        <!-- Sidebar -->
        <?php if ($role == 'admin') {
            include "../admin/include/admin_sidebar.php";
        } else {
            include "../admin/include/agent_sidebar.php";
        } ?>