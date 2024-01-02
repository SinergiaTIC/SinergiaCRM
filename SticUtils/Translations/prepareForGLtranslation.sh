# CONFIGURATION
TRANSLATION_WORK_FOLDER=~/code/translation
SOURCE=suitecrm
LANGUAGE=gl
# CONFIGURATION END

SINERGIACRM_FOLDER=$TRANSLATION_WORK_FOLDER/$SOURCE
LANGUAGE_FOLDER=$TRANSLATION_WORK_FOLDER/$LANGUAGE

if [ -d "$SINERGIACRM_FOLDER" ]; then
    echo "Ya existe la carpeta $SINERGIACRM_FOLDER. Para lanzar el proceso esta carpeta debe crearse de nuevo. Si es esto lo que quieres hacer, bórrala manualmente."
    exit 0
fi

if [ -d "$SINERGIACRM_FOLDER" ]; then
    echo "Ya existe la carpeta $LANGUAGE_FOLDER. Para lanzar el proceso esta carpeta debe crearse de nuevo. Si es esto lo que quieres hacer, bórrala manualmente."
    exit 0
fi

mkdir -p $TRANSLATION_WORK_FOLDER

# rm -rf $SINERGIACRM_FOLDER
# rm -rf $LANGUAGE_FOLDER

echo "1) Clonando repositorios localmente."
git clone -b release --single-branch https://github.com/SinergiaTIC/SinergiaCRM-SuiteCRM.git $SINERGIACRM_FOLDER

cd $SINERGIACRM_FOLDER
REPO_VERSION=$(git --git-dir $SINERGIACRM_FOLDER/.git/ log --format='%s' -n 1 | cut -d# -f2 | cut -d' ' -f1)

git clone https://github.com/SinergiaTIC/SinergiaCRM-Galician-language.git $LANGUAGE_FOLDER

BRANCH_NAME="${LANGUAGE}_translation_${REPO_VERSION}"

echo
cd $LANGUAGE_FOLDER
echo $BRANCH_NAME

REMOTE_BRANCH_EXISTS=$(git branch -r | grep $BRANCH_NAME)
if [ -n "$REMOTE_BRANCH_EXISTS" ]; then
    echo "La rama ya existe en el repositorio $LANGUAGE. No se puede continuar."
    echo "Para repetir el proceso, elimina la rama remota $BRANCH_NAME, pero ten en cuenta que se perderán los cambios realizados hasta ahora."
    exit 0
else
    echo "No existe la rama $LANGUAGE. Se continúa normalmente."
fi

echo "2) Creando rama $BRANCH_NAME en el repositorio $LANGUAGE (local)."
git checkout -b "$BRANCH_NAME"

echo
echo "3) Copiando ficheros con nuevas traducciones en el repositorio $LANGUAGE (local)."
rsync -amv --exclude='vendor' --exclude='*lang.ext.php' --include='*gl_ES*.php' --include='*/' --exclude='*' $SINERGIACRM_FOLDER/ $LANGUAGE_FOLDER/

echo
echo "4) Creando commit con los ficheros del idioma en el repositorio $LANGUAGE (local)."
git add "."
git commit -m "Added new strings for translation $REPO_VERSION"

echo "5) Subiendo rama $BRANCH_NAME al repositorio $LANGUAGE (local -> remoto)."
git push --set-upstream origin $BRANCH_NAME

echo
echo "SIGUIENTES PASOS"
echo "----------------"
echo "6) En el repositorio de $LANGUAGE crear un pull request a partir de la rama $BRANCH_NAME. El PR debe nombrarse igual que la rama y debe asignarse a la persona traductora, de manera que esta recibirá un aviso por correo electrónico. No está de mas recordarle a la persona traductora, al menos en las primeras ocasiones, que, sin previo aviso, el proceso terminará **7 días antes** del siguiente despliegue de actualizaciones programado en SinergiaCRM." 
