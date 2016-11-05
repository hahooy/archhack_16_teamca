from django.conf.urls import url

from . import views

urlpatterns = [
	url(r'^$', views.index, name='index'),
    url(r'^search_shelter/$', views.search_shelter, name='search_shelter'),
    url(r'^reserve_bed/$', views.reserve_bed, name='reserve_bed'),
]