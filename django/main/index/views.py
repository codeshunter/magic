import json

from django.http import HttpResponse

# Create your views here.
from django.shortcuts import render


def index (request) :
    return render(request, 'index/index.html', {
        'data': {
            'test': '中文'
        }
    })


def aboutus (request) :
    return render(request, 'index/aboutus.html')