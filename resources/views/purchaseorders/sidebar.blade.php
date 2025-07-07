<style>
    .nav.navbar-nav.subnav li a {
        color: white;
    }
    .subnav.navbar-toggle .icon-bar {
    background-color: white;
    }
</style>
<nav class="subnavbar" style="background-color: #154284;">
    <div class="navbar-header">
        <!-- Collapsed Hamburger -->
        <button type="button" class="subnav navbar-toggle collapsed" data-toggle="collapse" data-target="#app-subnav">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" style="color:white;" href="#">
            Purchase Orders:
        </a>
    </div>
    <div class="collapse navbar-collapse" id="app-subnav">

        <ul class="nav navbar-nav subnav">
            <li><a href="/purchaseorders">List Purchase Orders</a></li>
            <li><a href="/purchaseorders/create">Create Purchase Order</a></li>
            <li><a href="/purchaseorders/detailbywarehouse">POs by Warehouse</a></li>
            <li><a href="/purchaseorders/detailbyvendor">POs by Vendor</a></li>
            <li><a href="/purchaseorders/detailbyconcept">POs by Concept</a></li>
            <li><a href="/purchaseorders/partialpendingrpt">Pending POs by Vendor</a></li>
            {{-- <li><a href="/purchaseorders/receive">Receive Purchase Order</a></li> --}}
        </ul>
    </div>
</nav>