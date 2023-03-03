function handler(data) {
  var postData = {};

  var dataArray = data.split('&');

  for (var i = 0; i < dataArray.length; i++) {
    var singleData = dataArray[i].split('=');
    postData[singleData[0]] = singleData[1];
  }

  return postData;
}

function redirect(r) {
  var redirect = 'payment-mirror';

  var body = r.requestBody;

  if (body && handler(body).ORDER_UUID) {
    redirect = 'payment-dmss';
  }

  r.log('REDIRECT:'+ redirect);

  r.internalRedirect(redirect);
}

