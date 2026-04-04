<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Progress</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white shadow-xl rounded-2xl p-6 w-full max-w-md">

            <!-- Header -->
            <div class="mb-4">
                <span class="text-xs text-blue-500 font-semibold">SYSTEM TASK: ACTIVE</span>
                <h2 class="text-xl font-bold mt-2">Exporting Report...</h2>
                <p id="message" class="text-sm text-gray-500 mt-1">
                    Menyiapkan data...
                </p>
            </div>

            <!-- Progress -->
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-1">
                    <span>Progress</span>
                    <span id="percent">0%</span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar"
                        class="bg-blue-500 h-2 rounded-full transition-all duration-500"
                        style="width: 0%">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mt-4 flex justify-between text-xs text-gray-500">
                <span id="status">pending</span>
                <span id="time">--</span>
            </div>

            <!-- Download Button -->
            <div class="mt-6 text-center hidden" id="action-container">
                <a id="download-btn"
                    class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition">
                    Download File
                </a>
            </div>

            <!-- Error Message -->
            <div class="mt-4 text-center hidden" id="error-container">
                <p class="text-red-500 text-sm font-medium" id="error-message"></p>
            </div>

        </div>
    </div>

    <script>
        const exportId = {{ $id }};
        let interval = null;

        function updateUI(data) {
            document.getElementById('percent').innerText = data.progress + '%';
            document.getElementById('message').innerText = data.message ?? '-';
            document.getElementById('status').innerText = data.status;
            document.getElementById('progress-bar').style.width = data.progress + '%';

            // DONE → tampilkan tombol download
            if (data.status === 'done') {
                document.getElementById('action-container').classList.remove('hidden');

                const btn = document.getElementById('download-btn');
                btn.href = `/export/download/${exportId}`;

                clearInterval(interval);
            }

            // FAILED → tampilkan error
            if (data.status === 'failed') {
                document.getElementById('error-container').classList.remove('hidden');
                document.getElementById('error-message').innerText =
                    data.error ?? 'Export gagal';

                clearInterval(interval);
            }
        }

        function fetchStatus() {
            fetch(`/export/status/${exportId}`)
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    updateUI(data);
                })
                .catch(err => {
                    console.error(err);
                });
        }

        // polling tiap 1 detik
        interval = setInterval(fetchStatus, 1000);

        // initial load
        fetchStatus();
    </script>

</body>

</html>
