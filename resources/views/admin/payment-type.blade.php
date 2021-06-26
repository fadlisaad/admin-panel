<canvas id="doughnut" width="200" height="200"></canvas>
<script>
$(function () {

    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    {{ $method['FPX'] }},
                    {{ $method['Kad Kredit/Debit'] }}
                ],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)'
                ]
            }],
            labels: [
                'FPX',
                'Kad Kredit/Debit'
            ]
        },
        options: {
            maintainAspectRatio: false
        }
    };

    var ctx = document.getElementById('doughnut').getContext('2d');
    new Chart(ctx, config);
});
</script>