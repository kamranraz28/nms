<footer class="main-footer">
    <!-- To the right -->
    <div style="text-align: center">
      @if (@$sitesetting->{'footer_'. app()->getLocale()} )
        {!! $sitesetting->{'footer_'. app()->getLocale()} !!}
      @else
        <strong>Copyright &copy; 2020-- <?php echo date("Y"); ?> <a href="#">Documentation For Digital Platform </a>.</strong> All rights reserved.
      @endif
    </div>

    
</footer>