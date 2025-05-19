    // Função para alternar entre seções de ajuda
    function showSection(sectionId) {
        // Esconde todas as seções
        const sections = document.querySelectorAll('.section');
        sections.forEach(section => {
            section.classList.remove('active');
        });

        // Exibe a seção desejada
        const selectedSection = document.getElementById(sectionId);
        selectedSection.classList.add('active');
    }

    // Exibe os primeiros passos por padrão quando a página carrega
    document.addEventListener('DOMContentLoaded', () => {
        showSection('primeiros-passos');
    });