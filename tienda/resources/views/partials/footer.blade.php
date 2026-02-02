<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- Sobre nosotros -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5>MangUP</h5>
                <p class="text-muted">
                    Tu tienda online de confianza para manga, figuras y merchandising anime. 
                    Envíos a toda España con los mejores precios.
                </p>
                <div class="social-links">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            
            <!-- Enlaces rápidos -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Tienda</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('productos.index', ['tipo' => 'manga']) }}">Mangas</a></li>
                    <li class="mb-2"><a href="{{ route('productos.index', ['tipo' => 'figura']) }}">Figuras</a></li>
                    <li class="mb-2"><a href="{{ route('productos.index', ['tipo' => 'merch']) }}">Merch</a></li>
                    <li class="mb-2"><a href="{{ route('productos.index', ['ordenar' => 'recientes']) }}">Novedades</a></li>
                    <li class="mb-2"><a href="{{ route('productos.index') }}">Catálogo</a></li>
                </ul>
            </div>
            
            <!-- Información -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Información</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}">Sobre nosotros</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}">Contacto</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}">Política de envío</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}">Devoluciones</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}">FAQ</a></li>
                </ul>
            </div>
            
            <!-- Contacto -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5>Contacto</h5>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i> Calle Ejemplo 123, Madrid
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i> +34 912 345 678
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i> info@mangup.com
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock me-2"></i> Lun - Vie: 9:00 - 18:00
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom text-center text-muted">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} MangUP. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('home') }}" class="me-3">Términos y condiciones</a>
                    <a href="{{ route('home') }}">Política de privacidad</a>
                </div>
            </div>
        </div>
    </div>
</footer>
