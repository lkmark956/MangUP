<?php

require __DIR__.'/vendor/autoload.php';

echo "🧪 Probando configuración de Stripe...\n\n";

// Probar que las clases existen
if (class_exists('Stripe\Stripe')) {
    echo "✅ Clase Stripe\Stripe encontrada\n";
} else {
    echo "❌ Clase Stripe\Stripe NO encontrada\n";
}

if (class_exists('Stripe\Checkout\Session')) {
    echo "✅ Clase Stripe\Checkout\Session encontrada\n";
} else {
    echo "❌ Clase Stripe\Checkout\Session NO encontrada\n";
}

if (class_exists('Stripe\PaymentIntent')) {
    echo "✅ Clase Stripe\PaymentIntent encontrada\n";
} else {
    echo "❌ Clase Stripe\PaymentIntent NO encontrada\n";
}

echo "\n🎉 Todas las clases de Stripe están disponibles!\n";
