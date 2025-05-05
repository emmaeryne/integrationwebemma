import cv2
import sys

def detect_face(image_path):
    # Load face classifier
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

    # Read and check image
    image = cv2.imread(image_path)
    if image is None:
        print("Erreur: Impossible de lire l'image")
        return False

    # Convert to grayscale
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # Detect faces
    faces = face_cascade.detectMultiScale(
        gray,
        scaleFactor=1.1,
        minNeighbors=5,
        minSize=(30, 30)
    )

    # Check if any face is detected
    if len(faces) > 0:
        print("Un visage humain")
        return True
    else:
        print("Aucun visage humain détecté.")
        return False

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python face_detection.py <image_path>")
        sys.exit(1)
    
    detect_face(sys.argv[1])
