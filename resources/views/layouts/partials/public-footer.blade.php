{{-- Pied de page public, partagé par layouts.terminal et layouts.public. --}}
<footer id="contact">
    <div class="footer-content">
        <h3 class="section-title">Contact</h3>
        <div class="social-links">
            <a href="mailto:david.grougi@gmail.com">Email</a>
            <a href="https://github.com/doko972" target="_blank" rel="noopener noreferrer">GitHub</a>
            <a href="{{ route('contact') }}">Formulaire de contact</a>
        </div>
        <p style="margin-top: 30px; opacity: 0.7">
            © {{ date('Y') }} Terminal Portfolio | David GROUGI
        </p>
        <p style="opacity: 0.5; font-size: 0.9rem">user@terminal:~$</p>
    </div>
</footer>
