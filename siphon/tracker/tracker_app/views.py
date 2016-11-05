import re
import json

from django.shortcuts import render, redirect, get_object_or_404
from django.http import HttpResponse, HttpResponseRedirect, HttpResponseForbidden, HttpResponseNotFound
from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.models import User
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from django.contrib.gis.geos import Point
from django.contrib.gis.measure import Distance
from tracker.models import Shelter
from django.db.models import F, Q, Count


# Create your views here.

def index(request):
    shelters = Shelter.objects.all()
    return HttpResponse(json.dumps([
    {'name': s.name,
    'address': s.address,
    'description': s.description,
    'phone': s.phone,
    'lat': s.point[0],
    'lon': s.point[1],
    'all_beds': s.all_beds,
    'male_only': s.male_only,
    'female': s.female_only,
    'youth_only': s.youth_only} for s in shelters]))



def search_shelter(request):
    if request.method == 'POST':
        last_name = request.POST.get('lastName')
        birthday = request.POST.get('birthday')
        ethnicity = request.POST.get('ethnicity')
        sex = request.POST.get('sex')
        ssn = request.POST.get('ssn')
        background = request.POST.get('background')
        image = request.POST.get('image')
        lat = float(request.POST.get('lat'))
        lon = float(request.POST.get('lon'))
        if 'dist' in request.POST:
            dist = int(request.POST.get('dist'))
        else:
            dist = 10
        point = Point(lat, lon)


        shelters = Shelter.objects.annotate(Count('reservation'))\
                    .annotate(Count('residence'))\
                    .filter(point__distance_lt=(point, Distance(km=dist)),
                            all_beds__gt=F('reservation__count') + F('residence__count'))

        if sex == 'M':
            shelters = shelters.filter(female_only=False)
        else:
            shelters = shelters.filter(male_only=False)

        return HttpResponse(json.dumps([
        {
        'id': s.id,
        'name': s.name,
        'address': s.address,
        'description': s.description,
        'contact': s.phone,
        'lat': s.point[0],
        'lon': s.point[1],
        'all_beds': s.all_beds,
        'num_residences': s.residence__count,
        'num_reservations': s.reservation__count,
        'available_beds': s.all_beds - s.residence__count - s.reservation__count,
        'male_only': s.male_only,
        'female': s.female_only,
        'youth_only': s.youth_only} for s in shelters]))

    else:
        return HttpResponse('POST only!')


def reserve_bed(request):
    if request.method == 'POST':
        shelter_id = request.POST.get('shelter_id')
        birthday = request.POST.get('birthday')
        ethnicity = request.POST.get('ethnicity')
        sex = request.POST.get('sex')
        ssn = request.POST.get('ssn')
        background = request.POST.get('background')
        image = request.POST.get('image')
        lat = float(request.POST.get('lat'))
        lon = float(request.POST.get('lon'))
        if 'dist' in request.POST:
            dist = int(request.POST.get('dist'))
        else:
            dist = 10
        point = Point(lat, lon)


        shelters = Shelter.objects.annotate(Count('reservation'))\
                    .annotate(Count('residence'))\
                    .filter(point__distance_lt=(point, Distance(km=dist)),
                            all_beds__gt=F('reservation__count') + F('residence__count'))

        if sex == 'M':
            shelters = shelters.filter(female_only=False)
        else:
            shelters = shelters.filter(male_only=False)

        return HttpResponse(json.dumps([
        {
        'id': s.id,
        'name': s.name,
        'address': s.address,
        'description': s.description,
        'contact': s.phone,
        'lat': s.point[0],
        'lon': s.point[1],
        'all_beds': s.all_beds,
        'num_residences': s.residence__count,
        'num_reservations': s.reservation__count,
        'available_beds': s.all_beds - s.residence__count - s.reservation__count,
        'male_only': s.male_only,
        'female': s.female_only,
        'youth_only': s.youth_only} for s in shelters]))

    else:
        return HttpResponse('POST only!')


