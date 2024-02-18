<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF to DOC Converter</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/css/fileinput.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.1/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        nav {
            background-color: #ffffff;
            color: #e5322d;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1em 0;
            position: fixed;
            width: 100%;
            z-index: 1000;
        }

        nav a {
            color: #e5322d;
            text-decoration: none;
            padding: 0.5em 1em;
            margin: 0 1em;
            transition: color 0.3s ease-in-out;
        }

        nav a:hover {
            color: #333;
        }

        main {
            padding: 2em;
            margin-top: 60px; /* Adjust based on navbar height */
        }

        #converter {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2em;
        }

        #fileDropArea {
            height: 300px;
            border: 2px dashed #aaa;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: background-color 0.3s ease-in-out;
        }

        #fileDropArea:hover {
            background-color: #f9f9f9;
        }

        #convertBtn {
            background-color: #e5322d;
            color: #fff;
            padding: 0.7em 1.5em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        #convertBtn:hover {
            background-color: #c72339;
        }

        footer {
            background-color: #ffffff;
            color: #e5322d;
            text-align: center;
            padding: 1em 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .file-input {
            border: none !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">
            <img src="your-logo.png" alt="Logo" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div id="converter" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div id="fileDropArea" class="mb-4">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                        <p class="mb-2">Drag and drop your PDF file here</p>
                        <input id="fileInput" type="file" class="file">
                    </div>
                    
                    <button id="convertBtn" class="btn btn-lg btn-block" onclick="convertDocument()">Convert to DOC</button>
                </div>
            </div>
        </div>
    </main>

    <footer>
        &copy; 2024 PDF to DOC Converter. All rights reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/js/fileinput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.3/themes/fa/theme.js"></script>
    <script>
        // Initialize File Input plugin
        $("#fileInput").fileinput();

        // Add your conversion logic here
        function convertDocument() {
            // Implement PDF to DOC conversion logic
            alert("Conversion logic will be implemented here.");
        }
    </script>
</body>
</html>
