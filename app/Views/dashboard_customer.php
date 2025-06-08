<?php
// Dummy veriler
$topCoffees = [
  ['name' => 'Latte', 'total' => 10, 'image' => 'https://cdn-icons-png.flaticon.com/512/4355/4355576.png'],
  ['name' => 'Espresso', 'total' => 8, 'image' => 'https://cdn-icons-png.flaticon.com/512/3075/3075977.png'],
  ['name' => 'Cappuccino', 'total' => 6, 'image' => 'https://cdn-icons-png.flaticon.com/512/1354/1354884.png'],
];

$favCoffees = [
  ['name' => 'Espresso', 'favCount' => 7, 'image' => 'https://cdn-icons-png.flaticon.com/512/3075/3075977.png'],
  ['name' => 'Latte', 'favCount' => 6, 'image' => 'https://cdn-icons-png.flaticon.com/512/4355/4355576.png'],
  ['name' => 'Mocha', 'favCount' => 4, 'image' => 'https://cdn-icons-png.flaticon.com/512/4365/4365444.png'],
];

$topIngredients = [
  ['name' => 'Süt', 'usedIn' => 15, 'image' => 'https://cdn-icons-png.flaticon.com/512/2913/2913466.png'],
  ['name' => 'Kakao', 'usedIn' => 9, 'image' => 'https://cdn-icons-png.flaticon.com/512/6511/6511542.png'],
  ['name' => 'Şeker', 'usedIn' => 7, 'image' => 'https://cdn-icons-png.flaticon.com/512/5906/5906831.png'],
];

// Aylık toplam kahve tüketimi (Ocak - Aralık)
$monthlyCoffeeTotals = [
  'Ocak' => 12,
  'Şubat' => 15,
  'Mart' => 10,
  'Nisan' => 18,
  'Mayıs' => 20,
  'Haziran' => 22,
  'Temmuz' => 17,
  'Ağustos' => 14,
  'Eylül' => 19,
  'Ekim' => 21,
  'Kasım' => 16,
  'Aralık' => 23,
];
?>

<?php require 'partials/header.php'; ?>

<main class="container mx-auto p-6 space-y-10 max-w-7xl">

  <!-- İstatistikler -->
  <section class="flex flex-wrap gap-8 justify-center">
    <!-- En Çok İçilen Kahveler -->
    <div class="flex-1 min-w-[300px] max-w-md bg-white p-6 rounded-lg shadow-lg">
      <h3 class="text-xl font-semibold mb-4 text-center">En Çok İçtiğin 3 Kahve</h3>
      <div class="space-y-6">
        <?php foreach ($topCoffees as $i => $coffee): ?>
          <div class="flex items-center space-x-4 opacity-0 animate-fade-in-up" style="animation-delay: <?= $i * 0.15 ?>s">
            <img src="<?= $coffee['image'] ?>" alt="<?= htmlspecialchars($coffee['name']) ?>" class="w-20 h-20 object-cover rounded-lg shadow-md">
            <div>
              <h4 class="font-semibold"><?= htmlspecialchars($coffee['name']) ?></h4>
              <p class="text-gray-600"><?= $coffee['total'] ?> kez</p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- En Çok Tercih Edilen Kahveler -->
    <div class="flex-1 min-w-[300px] max-w-md bg-white p-6 rounded-lg shadow-lg">
      <h3 class="text-xl font-semibold mb-4 text-center">En Çok Tercih Ettiğin 3 Kahve</h3>
      <div class="space-y-6">
        <?php foreach ($favCoffees as $i => $coffee): ?>
          <div class="flex items-center space-x-4 opacity-0 animate-fade-in-up" style="animation-delay: <?= $i * 0.15 ?>s">
            <img src="<?= $coffee['image'] ?>" alt="<?= htmlspecialchars($coffee['name']) ?>" class="w-20 h-20 object-cover rounded-lg shadow-md">
            <div>
              <h4 class="font-semibold"><?= htmlspecialchars($coffee['name']) ?></h4>
              <p class="text-gray-600"><?= $coffee['favCount'] ?> kez</p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- En Çok Tercih Edilen Malzemeler -->
    <div class="flex-1 min-w-[300px] max-w-md bg-white p-6 rounded-lg shadow-lg">
      <h3 class="text-xl font-semibold mb-4 text-center">En Çok Tercih Edilen Malzemeler</h3>
      <div class="space-y-4">
        <?php foreach ($topIngredients as $i => $ing): ?>
          <div class="flex items-center space-x-4 opacity-0 animate-fade-in-up" style="animation-delay: <?= $i * 0.15 ?>s">
            <img src="<?= $ing['image'] ?>" alt="<?= htmlspecialchars($ing['name']) ?>" class="w-16 h-16 object-cover rounded-lg shadow-md">
            <div>
              <h4 class="font-semibold"><?= htmlspecialchars($ing['name']) ?></h4>
              <p class="text-gray-600"><?= $ing['usedIn'] ?> kez</p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Grafik -->
  <section class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center">Aylık Toplam Kahve Tüketimi</h2>
    <canvas id="monthlyCoffeeChart" class="w-full h-64"></canvas>
  </section>

</main>

<style>
  @keyframes fade-in-up {
    0% {
      opacity: 0;
      transform: translateY(15px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
  .animate-fade-in-up {
    animation: fade-in-up 0.5s forwards;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = <?= json_encode(array_keys($monthlyCoffeeTotals)) ?>;
  const data = <?= json_encode(array_values($monthlyCoffeeTotals)) ?>;

  const ctx = document.getElementById('monthlyCoffeeChart').getContext('2d');
  const monthlyCoffeeChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Toplam Kahve',
        data: data,
        borderColor: 'rgba(234, 88, 12, 1)',
        backgroundColor: 'rgba(234, 88, 12, 0.3)',
        fill: true,
        tension: 0.3,
        pointRadius: 5,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          stepSize: 5,
          ticks: { precision: 0 }
        }
      },
      plugins: {
        legend: {
          labels: {
            font: { size: 14, weight: 'bold' }
          }
        }
      }
    }
  });
</script>

<?php require 'partials/footer.php'; ?>
