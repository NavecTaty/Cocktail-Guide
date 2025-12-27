//CODE JS POUR LA COMPLETION DE RECHERCHE
document.addEventListener("DOMContentLoaded", () => {

    /**
     * Initialise l'autocomplétion pour un champ input
     * @param {string} inputId
     * @param {string} suggestionsId
     */
    function initRecherche(inputId, suggestionsId) {

        const input = document.getElementById(inputId);
        const suggestions = document.getElementById(suggestionsId);

        if (!input || !suggestions) return;

        input.addEventListener("keyup", () => {
            const valeur = input.value;

            // Dernier terme après virgule
            const termes = valeur.split(",");
            const termeCourant = termes[termes.length - 1].trim();

            // Trop court → pas d'AJAX
            if (termeCourant.length < 2) {
                suggestions.innerHTML = "";
                return;
            }

            // Appel AJAX
            fetch(`/Cocktail-Guide/src/ajax/research.php?term=${encodeURIComponent(termeCourant)}`)
                .then(response => response.json())
                .then(data => {
                    suggestions.innerHTML = "";

                    data.forEach(nom => {
                        const li = document.createElement("li");
                        li.textContent = nom;
                        li.classList.add("suggestion-item");

                        li.addEventListener("click", () => {
                            // Remplacer uniquement le dernier terme
                            termes[termes.length - 1] = " " + nom;
                            input.value = termes.join(",").replace(/^ /, "");
                            suggestions.innerHTML = "";
                        });

                        suggestions.appendChild(li);
                    });
                })
                .catch(() => {
                    suggestions.innerHTML = "";
                });
        });

        // Fermer la liste si clic ailleurs
        document.addEventListener("click", (e) => {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.innerHTML = "";
            }
        });
    }

    // Initialisation des champs include / exclude
    initRecherche("include-input", "include-suggestions");
    initRecherche("exclude-input", "exclude-suggestions");

    /**
     
     * Copie les valeurs visibles vers les champs hidden
     */
    const form = document.getElementById("form-recherche");

    if (form) {
        form.addEventListener("submit", () => {
            const includeInput = document.getElementById("include-input");
            const excludeInput = document.getElementById("exclude-input");

            const includeHidden = document.getElementById("include-hidden");
            const excludeHidden = document.getElementById("exclude-hidden");

            if (includeInput && includeHidden) {
                includeHidden.value = includeInput.value;
            }

            if (excludeInput && excludeHidden) {
                excludeHidden.value = excludeInput.value;
            }
        });
    }

});


