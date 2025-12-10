<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center" id="loading-spinner" style="display: none;">
    <div class="bg-white p-8 rounded-xl shadow-xl flex items-center border-l-4 border-primary-500">
        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-primary-600 mr-4"></div>
        <div>
            <span class="text-primary-800 font-bold text-lg">Memproses...</span>
            <p class="text-sm text-gray-600">Mohon tunggu sebentar</p>
        </div>
    </div>
</div>

<script>
    function showLoading() {
        document.getElementById('loading-spinner').style.display = 'flex';
    }

    function hideLoading() {
        document.getElementById('loading-spinner').style.display = 'none';
    }

    // Auto show loading on form submissions
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                showLoading();
            });
        });
    });
</script>