<?php include "Views/Templates/header.php"; ?>

<div class="container-fluid">
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card text-white bg-primary h-100 shadow">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title">Conductores (último año)</h5>
            <h3 class="card-text"><?php echo $data['conductores']['total'] ?></h3>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-success h-100 shadow">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title">Empresas (último año)</h5>
            <h3 class="card-text"><?php echo $data['empresas']['total'] ?></h3>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-info h-100 shadow">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title">Pagos (último año)</h5>
            <h3 class="card-text"><?php echo $data['pagos']['total'] ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bajas de Nómina -->
  <div class="card mb-4">
    <div class="card-header bg-light">
      Bajas de Nómina
    </div>
    <div class="card-body text-success">
      No se encontró información de este año
    </div>
  </div>

  <!-- Calendario -->
  <div class="card calendario mb-4 float-start" style="max-width: 500px;">
    <div class="card-header bg-light">
      Calendario del Mes Actual
    </div>
    <div class="card-body">
      <div class="calendar">
        <div class="calendar-header text-center fw-bold mb-3" id="calendar-title"></div>
        <div class="calendar-grid" id="calendar-days">
          <div class="day-name">Dom</div>
          <div class="day-name">Lun</div>
          <div class="day-name">Mar</div>
          <div class="day-name">Mié</div>
          <div class="day-name">Jue</div>
          <div class="day-name">Vie</div>
          <div class="day-name">Sáb</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mapa -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="card shadow">
        <div class="card-header bg-light">
          Mapa de la Región La Libertad
        </div>
        <div class="card-body">
          <div id="mapaLaLibertad" style="height: 500px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>




<!-- Estilos mejorados del calendario -->
<style>
    .calendar {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }

    .calendar-header {
        font-size: 1.2rem;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .day-name,
    .day {
        text-align: center;
        padding: 6px;
        font-size: 13px;
        border-radius: 4px;
    }

    .day-name {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .day {
        background-color: #fdfdfd;
        border: 1px solid #ccc;
    }

    .day.today {
        background-color: #198754;
        color: white;
        font-weight: bold;
    }
</style>

<!-- Script de generación del calendario -->
<script>
    const map = L.map('mapaLaLibertad').setView([-8.1153, -78.9901], 8);

    // Cargar fondo del mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Marcadores de provincias
    const provincias = [{
            nombre: 'Trujillo',
            coords: [-8.1153, -78.9901]
        },
        {
            nombre: 'Otuzco',
            coords: [-7.9008, -78.5802]
        },
        {
            nombre: 'Sánchez Carrión',
            coords: [-7.8203, -78.0442]
        },
        {
            nombre: 'Pacasmayo',
            coords: [-7.4086, -79.5719]
        },
        {
            nombre: 'Chepén',
            coords: [-7.2263, -79.4293]
        },
        {
            nombre: 'Pataz',
            coords: [-8.0546, -77.4044]
        },
        {
            nombre: 'Julcán',
            coords: [-7.6552, -78.4303]
        }
    ];

    provincias.forEach(p => {
        L.marker(p.coords).addTo(map).bindPopup(p.nombre);
    });

    // Cargar contorno de La Libertad
    fetch('geojson/la_libertad.geojson')
        .then(res => res.json())
        .then(data => {
            L.geoJSON(data, {
                style: {
                    color: 'blue',
                    weight: 2,
                    fillOpacity: 0.15
                }
            }).addTo(map);
        });
    document.addEventListener("DOMContentLoaded", () => {
        const calendarDays = document.getElementById('calendar-days');
        const calendarTitle = document.getElementById('calendar-title');

        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth();
        const currentDate = today.getDate();

        const meses = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];
        calendarTitle.textContent = `${meses[currentMonth]} ${currentYear}`;

        const primerDia = new Date(currentYear, currentMonth, 1).getDay();
        const totalDias = new Date(currentYear, currentMonth + 1, 0).getDate();

        // Limpiar días anteriores si los hay
        calendarDays.innerHTML = `
            <div class="day-name">Dom</div>
            <div class="day-name">Lun</div>
            <div class="day-name">Mar</div>
            <div class="day-name">Mié</div>
            <div class="day-name">Jue</div>
            <div class="day-name">Vie</div>
            <div class="day-name">Sáb</div>
        `;

        // Espacios vacíos antes del primer día
        for (let i = 0; i < primerDia; i++) {
            const emptyCell = document.createElement('div');
            calendarDays.appendChild(emptyCell);
        }

        // Días del mes
        for (let dia = 1; dia <= totalDias; dia++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'day';
            if (dia === currentDate) {
                dayElement.classList.add('today');
            }
            dayElement.textContent = dia;
            calendarDays.appendChild(dayElement);
        }
    });
</script>

<?php include "Views/Templates/footer.php"; ?>