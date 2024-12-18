from PIL import Image
import sys

def process_image(input_path, output_path, thumbnail_path):
    try:
        # Ouvrir l'image en mode paresseux pour minimiser la mémoire utilisée
        with Image.open(input_path) as img:
            # Sauvegarder l'image principale sans redimensionnement
            img.save(output_path, "WEBP", quality=70, optimize=True)  # "optimize=True" pour réduire la taille du fichier

            # Créer une copie pour la miniature
            height = 300
            thumbnail = img.copy()
            thumbnail.thumbnail((thumbnail.width, height), Image.LANCZOS)  # Filtre pour meilleure qualité
            thumbnail.save(thumbnail_path, "WEBP", quality=80, optimize=True)

            print("Image traitée avec succès")
    except Exception as e:
        print(f"Erreur lors du traitement de l'image : {e}")
        sys.exit(1)

if __name__ == "__main__":
    input_path = sys.argv[1]
    output_path = sys.argv[2]
    thumbnail_path = sys.argv[3]
    process_image(input_path, output_path, thumbnail_path)
