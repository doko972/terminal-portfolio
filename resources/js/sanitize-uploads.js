// ============================================
// ASSAINISSEMENT DES NOMS DE FICHIERS ENVOYÉS
// ============================================
//
// Une capture d'écran Windows arrive nommée « Capture d'écran 2026-08-18
// 143052.png ». L'apostrophe et les espaces voyagent tels quels dans l'en-tête
// Content-Disposition du corps multipart, et les pare-feux applicatifs des
// hébergeurs mutualisés (ModSecurity) y voient une tentative d'injection : la
// requête est rejetée en 403 avant même d'atteindre PHP.
//
// Impossible de corriger côté serveur, puisque rien n'y parvient. On renomme
// donc chaque fichier au moment de la soumission. Sans conséquence sur le
// stockage : Laravel réattribue de toute façon un nom aléatoire via store().

/**
 * Réduit un nom de fichier à [a-z0-9-] en conservant son extension.
 */
export function sanitizeFileName(original, index = 0) {
    const dot = original.lastIndexOf('.');

    const extension =
        (dot > 0 ? original.slice(dot + 1) : '')
            .replace(/[^a-zA-Z0-9]/g, '')
            .toLowerCase()
            .slice(0, 5) || 'bin';

    const base =
        (dot > 0 ? original.slice(0, dot) : original)
            .normalize('NFD') // sépare chaque lettre de son accent
            .replace(/\p{Diacritic}/gu, '') // « écran » -> « ecran », pas « -cran »
            .replace(/[^a-zA-Z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .toLowerCase()
            .slice(0, 50) || 'fichier';

    return `${base}-${index + 1}.${extension}`;
}

/**
 * Remplace le contenu d'un input file par les mêmes fichiers renommés.
 */
function sanitizeInput(input) {
    if (!input.files || input.files.length === 0) {
        return;
    }

    const transfer = new DataTransfer();

    Array.from(input.files).forEach((file, index) => {
        transfer.items.add(
            new File([file], sanitizeFileName(file.name, index), {
                type: file.type,
                lastModified: file.lastModified,
            })
        );
    });

    input.files = transfer.files;
}

// Phase de capture : on passe avant tout autre gestionnaire de soumission.
document.addEventListener(
    'submit',
    (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        form.querySelectorAll('input[type="file"]').forEach(sanitizeInput);
    },
    true
);
