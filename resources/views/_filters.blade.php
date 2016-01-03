<script>
    function quick() {
        for (var i = 0; i <= 8; i++) {
            tone();
            toneAxis('X');
            toneWave('saw');
            toneFreq(9);
            save();
            tone();
            toneAxis('Y');
            toneWave('saw');
            toneFreq(9);
            save();

        }
    }
</script>