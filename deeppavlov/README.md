FAQ Bot powered by DeepPavlov. Live Helper Chat [configuration example](https://doc.livehelperchat.com/docs/bot/deeppavlov-faq)

Build image with trained your data.

You should edit `Dockerfiles/deep/train/file.csv` with your data before building an image.

```shell
docker-compose -f docker-compose.yml build
docker-compose -f docker-compose.yml up
```

Run as service once it's build

```shell
docker-compose -f docker-compose.yml up -d
```

Testing

```
curl -X POST "http://localhost:5000/model" -H "accept: application/json" -H "Content-Type: application/json" -d "{\"q\":[\"how old are you?\"]}"
```

Response if you did not change train data

```json
[[["very young?."],[0.0023965203802132793,0.0011658039174728067,0.0008657494625574156,0.0005414606203846541,0.0007059206448602602,0.0008255833104981557,0.0005902784679946099,0.0027601158497330015,0.0007642232438947259,0.0004863716305588563,0.0003268471415419398,0.0014544600835888503,0.0004896593450576205,0.9866270059016439],[13]]]
```

Manual setup example.

```shell
source ./env/bin/activate
pip install deeppavlov
pip install --upgrade pip
pip install tensorflow

# Adjust these commands to your env
python -m deeppavlov download ./tfidf_logreg_en_faq.json
python -m deeppavlov install ./tfidf_logreg_en_faq.json
python -m deeppavlov train ./tfidf_logreg_en_faq.json
python -m deeppavlov riseapi ./tfidf_logreg_en_faq.json
```