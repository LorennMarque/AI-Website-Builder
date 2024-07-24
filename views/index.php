<?php
require("server/db.php");

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT DATE(created_at) AS date, COUNT(*) AS count FROM website_information GROUP BY DATE(created_at) ORDER BY DATE(created_at)";
$result = $conn->query($sql);

$dates = array();
$counts = array();

if ($result === FALSE) {
    echo "Error en la consulta: " . $conn->error;
} else if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['date'];
        $counts[] = $row['count'];
    }
}

$conn->close();

// Codificar los datos en JSON para usarlos en el gráfico
$dates_json = json_encode($dates);
$counts_json = json_encode($counts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico de Datos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="bg-white pb-6 sm:pb-8 lg:pb-12">
        <!-- banner - start -->
        <div class="relative flex flex-wrap bg-indigo-500 px-4 py-3 sm:flex-nowrap sm:items-center sm:justify-center sm:gap-3 sm:pr-8 md:px-8">
          <div class="order-1 mb-2 inline-block w-11/12 max-w-screen-sm text-sm text-white sm:order-none sm:mb-0 sm:w-auto md:text-base">Estamos estrenando nuestro sitio web! Los primeros 10 usuarios tendrán acceso <b>gratis</b>!</div>
      
          <a href="create.html" class="order-last inline-block w-full whitespace-nowrap rounded-lg bg-indigo-600 px-4 py-2 text-center text-xs font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-indigo-700 focus-visible:ring active:bg-indigo-800 sm:order-none sm:w-auto md:text-sm">Crear mi sitio web gratis</a>
      
          <!-- close button - start -->
          <div class="order-2 flex w-1/12 items-start justify-end sm:absolute sm:right-0 sm:order-none sm:mr-1 sm:w-auto xl:mr-3">
            <button type="button" class="text-white transition duration-100 hover:text-indigo-100 active:text-indigo-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <!-- close button - end -->
        </div>
        <!-- banner - end -->
      </div>
      <div class="bg-white pb-6 sm:pb-8 lg:pb-12">
        <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
          <header class="mb-8 flex items-center justify-between py-4 md:mb-12 md:py-8 xl:mb-16">
            <!-- logo - start -->
            <a href="/" class="inline-flex items-center gap-2.5 text-2xl font-bold text-black md:text-3xl" aria-label="logo">
              <svg width="95" height="94" viewBox="0 0 95 94" class="h-auto w-6 text-indigo-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M96 0V47L48 94H0V47L48 0H96Z" />
              </svg>
      
              Flowrift
            </a>
            <!-- logo - end -->
      
            <!-- nav - start -->
            <nav class="hidden gap-12 lg:flex">
              <a href="#" class="text-lg font-semibold text-indigo-500">Home</a>
              <a href="#" class="text-lg font-semibold text-gray-600 transition duration-100 hover:text-indigo-500 active:text-indigo-700">Beneficios</a>
              <a href="#" class="text-lg font-semibold text-gray-600 transition duration-100 hover:text-indigo-500 active:text-indigo-700">Precios</a>
              <a href="#" class="text-lg font-semibold text-gray-600 transition duration-100 hover:text-indigo-500 active:text-indigo-700">Información</a>
            </nav>
            <!-- nav - end -->
      
            <!-- buttons - start -->
            <a href="login.html" class="hidden rounded-lg bg-gray-200 px-8 py-3 text-center text-sm font-semibold text-gray-500 outline-none ring-indigo-300 transition duration-100 hover:bg-gray-300 focus-visible:ring active:text-gray-700 md:text-base lg:inline-block">Acceder</a>
      
            <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-2.5 py-2 text-sm font-semibold text-gray-500 ring-indigo-300 hover:bg-gray-300 focus-visible:ring active:text-gray-700 md:text-base lg:hidden">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
      
              Menu
            </button>
            <!-- buttons - end -->
          </header>
      
          <section class="flex flex-col justify-between gap-6 sm:gap-10 md:gap-16 lg:flex-row">
            <!-- content - start -->
            <div class="flex flex-col justify-center sm:text-center lg:py-12 lg:text-left xl:w-5/12 xl:py-24">
              <p class="mb-4 font-semibold text-indigo-500 md:mb-6 md:text-lg xl:text-xl">Una nueva forma de crear</p>
      
              <h1 class="mb-8 text-4xl font-bold text-black sm:text-5xl md:mb-12 md:text-6xl">Sitios Web gratis en menos de 5 minutos.</h1>
      
              <p class="mb-8 leading-relaxed text-gray-500 md:mb-12 lg:w-4/5 xl:text-lg">Diseñado para emprendedores y profesionales Argentinos que quieran publicitar su negocio de forma facil y rápida. Sin pensar en costos elevados ni mantenimiento.</p>
      
              <div class="flex flex-col gap-2.5 sm:flex-row sm:justify-center lg:justify-start">
                <a href="create.html" class="inline-block rounded-lg bg-indigo-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-100 hover:bg-indigo-600 focus-visible:ring active:bg-indigo-700 md:text-base">Crear Sitio Web Ahora</a>
      
                <a href="login.html" class="inline-block rounded-lg bg-gray-200 px-8 py-3 text-center text-sm font-semibold text-gray-500 outline-none ring-indigo-300 transition duration-100 hover:bg-gray-300 focus-visible:ring active:text-gray-700 md:text-base">Acceder</a>
              </div>
            </div>
            <!-- content - end -->
      
            <!-- image - start -->
            <div class="h-48 overflow-hidden rounded-lg bg-gray-100 shadow-lg lg:h-auto xl:w-5/12">
              <img src="https://images.unsplash.com/photo-1618004912476-29818d81ae2e?auto=format&q=75&fit=crop&w=1000" loading="lazy" alt="Photo by Fakurian Design" class="h-full w-full object-cover object-center" />
            </div>
            <!-- image - end -->
          </section>
        </div>
      </div>

    <div class="container mx-auto my-8 px-4">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row">
            <div class="w-full md:w-1/2" style="height:40vh">
                <canvas id="myChart"></canvas>
            </div>
            <div class="w-full md:w-1/2 p-6">
                <h2 class="text-2xl font-bold text-black mb-4">Transparencia en Nuestro Código</h2>
                <p class="text-gray-500 mb-4">Nos enorgullece ser transparentes con nuestro código y los datos que recopilamos. A continuación, puedes ver un gráfico que muestra la cantidad de sitios web creados por fecha.</p>
                <p class="text-gray-500">Creemos en la importancia de la transparencia y queremos que nuestros usuarios confíen en nosotros. Si tienes alguna pregunta, no dudes en contactarnos.</p>
            </div>
        </div>
    </div>

    <script>
        const dates = <?php echo $dates_json; ?>;
        const counts = <?php echo $counts_json; ?>;

        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Sitios Web Creados por fecha',
                    data: counts,
                    borderColor: '#6366F1',
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderWidth: 1,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Fecha'
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 20
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Conteo'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
