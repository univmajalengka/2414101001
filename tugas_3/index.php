<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Siswa Baru | SMK Coding</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- Gaya Global & Reset --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* --- Warna Tema --- */
        :root {
            --primary-color: #4A00E0;
            --secondary-color: #8E2DE2;
            --background-color: #f4f7f6;
            --card-background: #ffffff;
            --text-color: #333333;
            --label-color: #555555;
            --border-color: #dddddd;
            --input-focus: #667eea;
        }

        /* --- Layout Kontainer Utama --- */
        .form-container {
            background: var(--card-background);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
        }

        /* --- Header Form --- */
        .form-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .form-header h3 {
            font-size: 24px;
            font-weight: 600;
        }

        /* --- Isi Form --- */
        form {
            padding: 40px;
        }

        fieldset {
            border: none;
        }

        legend {
            /* Menyembunyikan legend default karena kita pakai header */
            position: absolute;
            left: -9999px;
        }

        p {
            margin-bottom: 20px;
        }

        /* --- Gaya Label --- */
        label {
            display: block;
            font-weight: 500;
            color: var(--label-color);
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* --- Gaya Input, Textarea, dan Select --- */
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: var(--text-color);
            background-color: #fcfcfc;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--input-focus);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* --- Gaya Radio Button (Kustom) --- */
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-weight: 400;
            color: var(--text-color);
        }

        input[type="radio"] {
            /* Menyembunyikan radio button bawaan */
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            margin-right: 8px;
            position: relative;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        input[type="radio"]:checked {
            border-color: var(--input-focus);
        }

        input[type="radio"]:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--input-focus);
            transition: all 0.3s ease;
        }

        input[type="radio"]:checked:after {
            transform: translate(-50%, -50%) scale(1);
        }

        /* --- Gaya Tombol Submit --- */
        input[type="submit"] {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(74, 0, 224, 0.3);
        }

    </style>
</head>

<body>

    <div class="form-container">
        <header class="form-header">
            <h3>Formulir Pendaftaran Siswa Baru</h3>
        </header>
        
        <form action="proses-pendaftaran.php" method="POST">
            
            <fieldset>
                <legend>Data Diri Siswa</legend>
                
                <p>
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required />
                </p>
                <p>
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" placeholder="Masukkan alamat lengkap Anda" required></textarea>
                </p>
                <p>
                    <label>Jenis Kelamin</label>
                    <div class="radio-group">
                        <label for="laki-laki">
                            <input type="radio" id="laki-laki" name="jenis_kelamin" value="laki-laki" required>
                            Laki-laki
                        </label>
                        <label for="perempuan">
                            <input type="radio" id="perempuan" name="jenis_kelamin" value="perempuan">
                            Perempuan
                        </label>
                    </div>
                </p>
                <p>
                    <label for="agama">Agama</label>
                    <select id="agama" name="agama" required>
                        <option value="">-- Pilih Agama --</option>
                        <option value="islam">Islam</option>
                        <option value="kristen">Kristen</option>
                        <option value="hindu">Hindu</option>
                        <option value="budha">Budha</option>
                        <option value="atheis">Atheis</option>
                    </select>
                </p>
                <p>
                    <label for="sekolah_asal">Sekolah Asal</label>
                    <input type="text" id="sekolah_asal" name="sekolah_asal" placeholder="Nama sekolah asal Anda" required />
                </p>
                <p>
                    <input type="submit" value="Daftar Sekarang" name="daftar" />
                </p>
                
            </fieldset>
        
        </form>
    </div>

</body>
</html>