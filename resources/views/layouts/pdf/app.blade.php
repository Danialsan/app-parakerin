<!DOCTYPE html>
<html lang="id">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
  <title>@yield('title', 'Official - PKL - SMK NEGERI 1 BLEGA')</title>

</head>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box
  }

  body {
    font-family: Arial, sans-serif;
    background-color: #fff;
    color: #000;
  }

  .table-content {
    border-left: 0.01em solid #000;
    border-right: 0.01em solid #000;
    border-top: 0.01em solid #000;
    border-bottom: 0.01em solid #000;
    border-collapse: collapse;
  }

  .text-nowrap {
    white-space: nowrap;
  }

  #download {
    display: block;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 100px 100px 50px 100px;
    border: transparent;
    border-radius: 10px;
    background: crimson;
    color: #fff;
    font-family: sans-serif;
    letter-spacing: 1px;
    cursor: pointer;
    animation: fire-animation 50s infinite;
    opacity: .9;
    transition: all .5s;
  }

  #download:active {
    font-size: .8rem;
    padding: 50px 80px 20px 80px;
  }

  @keyframes fire-animation {

    0%,
    100% {
      background: crimson;
      box-shadow: 0 0 100px crimson;
    }

    33% {
      background: #e7cf34;
      box-shadow: 0 0 100px #e9e213;
    }

    66% {
      background: #5be86e;
      box-shadow: 0 0 100px #00ff22;
    }
  }

  .print-only {
    display: none;
  }

  .page-break {
    page-break-before: always;
  }

  @media print {

    /* Mengatur ukuran kertas A4 */
    @page {
      size: A4 {{ $orientasi ?? '' }};
    }

    /*
        body {
            padding: 1cm;
        } */
    /* Menyesuaikan tampilan untuk pencetakan */
    /* Menyembunyikan elemen yang tidak perlu dicetak */
    .no-print,
    #download {
      display: none;
    }

    /* Menyertakan elemen yang hanya muncul saat dicetak */
    .print-only {
      display: block;
      /* margin: 10mm; */
    }
  }
</style>

{{-- {{ $style ?? '' }} --}}

<body>
  <button class="no-print" id="download" onclick="printAndHide()">
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-printer-fill"
      viewBox="0 0 16 16">
      <path
        d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1" />
      <path
        d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
    </svg>
    <h1 style="margin-top: 50px">Download</h1>
  </button>
  <div class="print-only">
    {{-- {{ $slot }} --}}
  </div>

  {{-- <x-script-custom>
        {{ $script ?? '' }}
    </x-script-custom> --}}
  <script>
    function printAndHide() {
      window.print();
    }
  </script>
</body>

</html>
