  <!-- Start Footer -->
  <div class="footer">&copy; 2023 <span>Teaching Table Organizer</span> All Right Reserved</div>
    <!-- End Footer -->

   <script>
    function printTable(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
   </script> 
</body>

</html>