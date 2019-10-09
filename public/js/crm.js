const events = document.querySelector(".info__eventsToDo");
const oldEvents = document.querySelector(".info__oldEventsToDo");

const eventsToDoNumbers = document.createElement('eventsToDoNumbers');
eventsToDoNumbers.value = todayEventsToDo;
eventsToDoNumbers.textContent = todayEventsToDo;
events.appendChild(eventsToDoNumbers);

const oldEventsToDoNumber = document.createElement('oldEventsToDoNumber');
oldEventsToDoNumber.value = oldEventsToDo;
oldEventsToDoNumber.textContent = oldEventsToDo;
oldEvents.appendChild(oldEventsToDoNumber);