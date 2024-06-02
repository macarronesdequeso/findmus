document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('orderSelect').addEventListener('change', handleSearchChange);
        document.getElementById('typeSelect').addEventListener('change', handleSearchChange);

        function handleSearchChange() {
            var q = document.getElementById('searchInput').value;
            var order = document.getElementById('orderSelect').value;
            var type = document.getElementById('typeSelect').value;
            window.location.href = `?q=${q}&type=${type}&order=${order}&page=1`;
        }
});