document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btn-generate-structure');
    const output = document.getElementById('output-generate');
  
    if (!btn || !output) return;
  
    btn.addEventListener('click', async () => {
      output.textContent = '⏳ Exécution du script...';
  
      try {
        const res = await fetch('/speakify/public/api/index.php?action=admin_tool&tool=generate_file_structure');const data = await res.json();
  
        if (data.success) {
          output.textContent = `✅ Succès :\n${data.output}`;
        } else {
          output.textContent = `❌ Échec :\n${data.output || data.error}`;
        }
      } catch (err) {
        output.textContent = '❌ Erreur réseau ou interne.';
        console.error(err);
      }
    });
  });
  