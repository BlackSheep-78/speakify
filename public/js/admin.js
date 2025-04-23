// File: /public/assets/js/admin.js

document.addEventListener('DOMContentLoaded', () => {
  // 🔧 Génération de structure
  const btnStructure = document.getElementById('btn-generate-structure');
  const outputStructure = document.getElementById('output-generate');

  if (btnStructure && outputStructure) {
    btnStructure.addEventListener('click', async () => {
      outputStructure.textContent = '⏳ Exécution du script...';

      try {
        const res = await app.api(`api/index.php?action=admin_tool&tool=generate_file_structure&token=${app.token}`);

        if (res.success) {
          outputStructure.textContent = `✅ Succès :\n${res.output}`;
        } else {
          outputStructure.textContent = `❌ Échec :
${res.output || res.error}

💡 Astuce : assurez-vous d'être connecté avec un compte administrateur pour exécuter cette action.`;
        }
      } catch (err) {
        outputStructure.textContent = '❌ Erreur réseau ou interne.';
        console.error(err);
      }
    });
  }

  // 🌍 Traduction automatique d'une phrase manquante
  const btnTranslate = document.getElementById('btn-translate-one');
  const outputTranslate = document.getElementById('output-translate');

  if (btnTranslate && outputTranslate) {
    btnTranslate.addEventListener('click', async () => {
      outputTranslate.textContent = '⏳ Envoi de la requête...';

      try {
        const res = await app.api(`api/index.php?action=translate_one&token=${app.token}`);

        if (res.success) {
          outputTranslate.textContent = `✅ Traduction effectuée.\n${res.from.toUpperCase()}: \"${res.original}\"\n${res.to.toUpperCase()}: \"${res.translated}\"`;
        } else {
          outputTranslate.textContent = `❌ Échec : ${res.error || 'Aucune donnée à traduire.'}`;
        }
      } catch (err) {
        console.error(err);
        outputTranslate.textContent = '❌ Erreur réseau ou serveur.';
      }
    });
  }

  // 🔊 TTS test
  const btnTTS = document.getElementById('btn-tts-trigger');
  const outputTTS = document.getElementById('output-tts');

  if (btnTTS && outputTTS) {
    btnTTS.addEventListener('click', async () => {
      outputTTS.textContent = "⏳ Requête en cours...";

      try {
        const result = await app.api(`api/index.php?action=tts_generate&admin_key=${app.token}`);
        if (result.success) {
          outputTTS.textContent = `✅ Audio généré : ${result.file}\n${result.lang?.split('-')[0]?.toUpperCase()}: \"${result.original || 'Texte inconnu'}\"`;
        } else {
          outputTTS.textContent = `❌ ${result.error || "Erreur inconnue"}`;
        }
      } catch (err) {
        outputTTS.textContent = "❌ Erreur réseau ou interne.";
        console.error(err);
      }
    });
  }
});
