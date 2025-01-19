<?php
$current_year = date('Y');
?>
</main>
<footer class="bg-[#1e1b4b] text-white mt-8 w-full">
    <!-- Newsletter Section -->
    <div class="w-full px-4 py-8">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-2xl font-bold mb-4">Blijf op de hoogte van het laatste voetbalnieuws</h2>
            <p class="text-gray-300 mb-6">Schrijf je in voor onze nieuwsbrief en ontvang wekelijks de beste updates</p>
            <form class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <input type="email" placeholder="Jouw e-mailadres" class="px-4 py-2 rounded-lg bg-[#2d2a5d] text-white w-full sm:w-96 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-300 w-full sm:w-auto">
                    Inschrijven
                </button>
            </form>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="w-full border-t border-[#2d2a5d]">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About Section -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Over VoetbalVisie</h3>
                    <p class="text-gray-300 mb-4">
                        VoetbalVisie is jouw ultieme bron voor voetbalnieuws, analyses en wedstrijdverslagen.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-blue-500 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-blue-500 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-blue-500 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-blue-500 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Snelle Links</h3>
                    <ul class="space-y-2">
                        <li><a href="<?php echo SITE_URL; ?>/blogs.php" class="text-gray-300 hover:text-blue-500 transition">Blogs</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/wedstrijden.php" class="text-gray-300 hover:text-blue-500 transition">Wedstrijden</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php" class="text-gray-300 hover:text-blue-500 transition">Contact</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/privacy.php" class="text-gray-300 hover:text-blue-500 transition">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Social Media Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Volg Ons</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-blue-500 transition">
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-blue-500 transition">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-blue-500 transition">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact Info</h3>
                    <div class="space-y-2">
                        <p class="text-gray-300">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Amsterdam, Nederland
                        </p>
                        <p class="text-gray-300">
                            <i class="fas fa-envelope mr-2"></i>
                            info@voetbalvisie.nl
                        </p>
                        <p class="text-gray-300">
                            <i class="fas fa-phone mr-2"></i>
                            +31 (0)20 123 4567
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright Section -->
    <div class="w-full border-t border-[#2d2a5d]">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-center md:text-left">
                    &copy; <?php echo $current_year; ?> VoetbalVisie. Alle rechten voorbehouden.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition">Algemene voorwaarden</a>
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html> 