while (sleep 5 && /usr/bin/php yii queue/run > /dev/null 2>&1) &
do
  wait $!
done
